<?

namespace postyou;


class VenoBox extends \ContentElement
{

    protected $strTemplate = "ce_venobox";
    private $boxID;
    private $galleryIndex = 1;

    public function __construct($objElement, $strColumn='main'){
        parent::__construct($objElement);
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
        $this->boxID = VenoHelper::getVenoBoxID();


        $this->Template->html = $this->getVenoElemsHtml($this->venoList,$this->boxID,$this->galleryIndex);
        $this->Template->boxClass = VenoHelper::getVenoBoxClass($this->boxID);
        $this->Template->js=$this->getJs($this->boxID);

    }

    private function getVenoElemsHtml($vlist=null,$boxId,$galleryIndex){
        $html = "";
        if (isset($vlist)) {
            $list = unserialize($vlist);

            foreach ($list as $key => $elem) {
                $linkCssClass = "";
                if ($key == 0) {
                    $linkCssClass .= "first ";
                }
                if ($key == count($list) - 1) {
                    $linkCssClass .= "last";
                }
                $vElem = new VenoElement($elem, $boxId, $galleryIndex, $linkCssClass);
                $html .= $vElem->buildHtml() . "\n";
            }
        }
        return $html;

    }

     public static function getJs($boxID){
        return "<script type=\"text/javascript\">
        $(document).ready(function() {
            var venoOptions={}
            if(typeof end_done  != 'undefined' && $.isFunction(end_done))
                venoOptions['callback']=end_done".
            "$('.".$boxID."').venobox(venoOptions);".
            "});</script>";
    }

}


?>