<?
/**
 * Venobox for Contao
 * Extension for Contao Open Source CMS (contao.org)
 *
 * Copyright (c) 2016 POSTYOU
 *
 * @package venobox
 * @author  Gerald Meier
 * @link    http://www.postyou.de
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
namespace postyou;


use Contao\Input;

class VenoBox extends \ContentElement
{

    protected $strTemplate = "ce_venobox";
    private $galleryIndex = 1;

    public function __construct($objElement, $strColumn = 'main')
    {
        parent::__construct($objElement,$strColumn);
        VenoHelper::loadVenoScripts();
    }
    
    public function generate()
    {

        if (TL_MODE == 'BE') {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . utf8_strtoupper('VenoBox') . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
            return $objTemplate->parse();
        }

        return parent::generate();
    }


    /**
     * Compile the content element
     */
    protected function compile()
    {
        $venoProperties= unserialize($this->venoList);
        $this->Template->html = $this->getVenoElemsHtml($venoProperties,$this->galleryIndex);
        $this->Template->js=$this->getJs($venoProperties,Input::get("venoboxOpen"));

    }

    private function getVenoElemsHtml($vlist=null,$galleryIndex){
        $html = "";
        if (isset($vlist)) {
            foreach ($vlist as $key => $elem) {
                $linkCssClass = "";
                if ($key == 0) {
                    $linkCssClass .= "first ";
                }
                if ($key == count($vlist) - 1) {
                    $linkCssClass .= "last";
                }
                $vElem = new VenoElement($elem, $galleryIndex, $linkCssClass);
                $html .= $vElem->buildHtml() . "\n";
            }
        }
        return $html;

    }

    public static function getJs($properties,$autoLoadID=null)
    {
        $strBuffer= "<script type=\"text/javascript\">
        $(document).ready(function() {
            var venoOptions={}\n
            if(typeof venobox_pre_open_callback  != 'undefined' && $.isFunction(venobox_pre_open_callback))
                venoOptions[\"pre_open_callback\"]=venobox_pre_open_callback;\n
			if(typeof venobox_post_open_callback  != 'undefined' && $.isFunction(venobox_post_open_callback))
                venoOptions[\"post_open_callback\"]=venobox_post_open_callback;\n
			if(typeof venobox_pre_close_callback  != 'undefined' && $.isFunction(venobox_pre_close_callback))
                venoOptions[\"pre_close_callback\"]=venobox_pre_close_callback;\n
			if(typeof venobox_post_close_callback  != 'undefined' && $.isFunction(venobox_post_close_callback))
                venoOptions[\"post_close_callback\"]=venobox_post_close_callback;\n
                if(typeof venobox_resize_close_callback  != 'undefined' && $.isFunction(venobox_resize_close_callback))
                venoOptions[\"post_resize_callback\"]=venobox_resize_close_callback;\n";
        foreach ($properties as $venobox) {
            $strBuffer.= "$('.".VenoHelper::getVenoBoxClass($venobox[5])."').venobox(venoOptions)";
            if (isset($autoLoadID) && !empty($autoLoadID) && $venobox[5]==$autoLoadID) {
                $strBuffer .= ".trigger('click');\n";
            } else {
                $strBuffer .= ";\n";
            }
        }
        $strBuffer.="});</script>";
        return $strBuffer;
    }

}


?>
