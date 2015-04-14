<?

namespace postyou;


class VenoBox extends \ContentElement
{

    protected $strTemplate = "ce_venobox";
    private $boxID;
    private $galleryIndex=1;

    public function generate(){

        if (TL_MODE == 'BE')
        {
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
        $this->boxID=uniqid('');
        $html="";
        if(isset($this->venoList)) {
            $this->Template->files = $this->venoList;
            $list=unserialize($this->venoList);
            
            foreach($list as $key=>$elem){
                $class="";
                if($key==0)
                    $class.="first ";
                if($key==count($list)-1)
                    $class.="last";

                $vElem=new VenoElement($elem,$this->boxID,$this->galleryIndex,$class);
               $html.=$vElem->buildHtml()."\n";
            }
        }
        $this->Template->html=$html;
        $this->Template->boxClass="venobox_".$this->boxID;



    }
}




?>