<?php

namespace postYou;

class VenoElement{

    private $type;
    private $gallery=1;
    private $href;
    private $description;
    private $text;

    private $class="";

    private $boxID;
    private $galleryID=0;

    function __construct($initArray,$boxID,$galleryID,$class){
        $this->type=$initArray[0];
//        $this->gallery=$initArray[1];
        $this->href=$initArray[2];
        $this->description=$initArray[3];
        $this->text=$initArray[4];

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
        $this->class=$class;

        $this->boxID=$boxID;
        if($this->gallery)
            $this->galleryID=$galleryID;
    }

    function buildHtml(){
        $str="";
        $str.="<a class='venobox_".$this->boxID." ".$this->class."'";
        if ($this->type!=0)
            $str.=" data-type='".$GLOBALS['TL_CONFIG']['VenoBox']['types'][$this->type]."'";
        $str.=" href='".$this->href."'";
        if($this->gallery)
            $str.=" data-gall='venoGallery_".$this->boxID."_".$this->galleryID."'";
        if(isset($this->description) && !empty($this->description))
            $str.=" title='".$this->description."'";
        $str.=">".$this->text."</a>";

        return $str;
    }

}