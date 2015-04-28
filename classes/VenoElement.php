<?php

namespace postyou;

class VenoElement{

    private $type;
    private $gallery=1;
    private $href;
    private $description;
    private $text;
    private $overlayCssClass="";

    private $linkCssClass="";

    private $boxID;
    private $galleryID=0;

    function __construct($initArray,$boxID,$galleryID,$class){
        $this->type=$initArray[0];
//        $this->gallery=$initArray[1];
        $this->href=$initArray[1];
        $this->description=$initArray[2];
        $this->text=$initArray[3];
        if(!empty($initArray[4]))
        $this->overlayCssClass=$initArray[4];

        if($this->type==0){
            //big Image Path
            $objFileBig = \FilesModel::findByPk($this->href);
            if ($objFileBig !== null && is_file(TL_ROOT . '/' . $objFileBig->path)) {
                $this->href=$objFileBig->path;
            }
            //Thumbnail path
            $objFileSmall = \FilesModel::findByPk($this->text);
            if ($objFileSmall !== null && is_file(TL_ROOT . '/' . $objFileSmall->path)) {
                $this->text="<img src='".$objFileSmall->path."'/>";
            }
        }

        $this->linkCssClass=$class;

        $this->boxID=$boxID;
        if($this->gallery)
            $this->galleryID=$galleryID;
    }

    function buildHtml(){
        $outputType=$this->type;
        if($this->type==6) {
            if(class_exists("PageAjax")) {
                $this->href = \PageAjax::getAjaxURL()."?" . $this->href;
                $outputType = 3;
            }
            else
                return "<p>install <a href=\"https://github.com/garyee/contao-page2ajax/\">page2ajax-extension</a> for this to work<p>";
        }
        $str="";
        $str.="<a class='venobox_".$this->boxID." ".$this->linkCssClass."'";
        if ($this->type!=0)
            $str.=" data-type='".$GLOBALS['TL_CONFIG']['VenoBox']['types'][$outputType]."'";
        $str.=" href='".$this->href;
        if($this->type==3 || $this->type==6) {
            if (strpos($this->href, "?")===false) {
                $str .= "?";
            } else {
                $str .= "&";
            }
            $str .= "REQUEST_TOKEN=" . REQUEST_TOKEN;
        }
        $str.="'";
        if($this->gallery)
            $str.=" data-gall='venoGallery_".$this->boxID."_".$this->galleryID."'";
        if(!empty($this->overlayCssClass))
            $str.=" data-css='".$this->overlayCssClass."'";
        if(isset($this->description) && !empty($this->description))
            $str.=" title='".$this->description."'";
        $str.=">".$this->text."</a>";

        return $str;
    }

}