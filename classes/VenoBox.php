<?

namespace postyou;


class VenoBox extends \ContentElement
{

    protected $strTemplate = "ce_venobox";
    private $boxID;
    private $galleryIndex = 1;

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
        $this->boxID = uniqid('');
        $html = "";
        if (isset($this->venoList)) {
            $this->Template->files = $this->venoList;
            $list = unserialize($this->venoList);

            foreach ($list as $key => $elem) {
                $linkCssClass = "";
                if ($key == 0) {
                    $linkCssClass .= "first ";
                }
                if ($key == count($list) - 1) {
                    $linkCssClass .= "last";
                }

                if (preg_match("/\{\{(([^\{\}])*)\}\}/", $elem[1])) {
                    $elem[1]=$this->replaceInsterTagsAjax($elem[1],$elem[0]);
                }

                $vElem = new VenoElement($elem, $this->boxID, $this->galleryIndex, $linkCssClass);
                $html .= $vElem->buildHtml() . "\n";
            }
        }
        $this->Template->html = $html;
        $this->Template->bgcolor = $this->$elem[4];
        $this->Template->boxClass = "venobox_" . $this->boxID;


    }


    private function replaceInsterTagsAjax($value,$type)
    {
        if (strpos($value, '{{') !== false) {
            $url=substr($value,0,strpos($value,"{{"));
            $tagTyp=substr($value,2,strpos($value,"::")-2);
            if($type==6)
                switch($tagTyp) {
                    case"link_url":
                        $url="action=page&g=1&id=";
                        break;
                    case"article":
                        $url="action=art&g=1&id=";
                        break;
                    default:
                        return "Insert_Tag_Could_Not_Be_Converted";
                }
            return $url. str_replace(array('{{'.$tagTyp.'::', '}}'), '', $value);
        }
        return "NO_Insert_Tag_FOUND";
    }


}

?>