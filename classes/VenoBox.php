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

    public function __construct($objElement, $strColumn = 'main')
    {
        parent::__construct($objElement,$strColumn);
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
        if ($this->type=="VenoBox") {
            $venoboxGen=new VenoGenerator($this->venoList);
            VenoElement::loadVenoScripts();
            $venoboxGen->setTemplateVars($this->Template);
        }

    }

}

?>
