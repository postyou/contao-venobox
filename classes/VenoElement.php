<?php

namespace postyou;

class VenoElement{

    private $type;
    private $gallery=1;
    private $href;
    private $description;
    private $text;
    private $overlayCssClass="";
    private $overlayColor="";

    private $linkCssClass="";

    private $boxID;
    private $galleryID=0;

    function __construct($initArray=array(),$boxID="",$galleryID=1,$class=""){
//        var_dump($initArray);
        $this->type=$initArray[0];
//        $this->gallery=$initArray[1];
        $this->href=$initArray[1];
        $this->description=$initArray[2];
        $this->text=$initArray[3];
        if(!empty($initArray[4]))
        $this->overlayColor=$initArray[4];

        $this->overlayCssClass="veno_".$GLOBALS['TL_CONFIG']['VenoBox']['types'][$this->type];

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

        $this->replaceInsterTagsAjax();
    }

    function buildHtml($justOpenTag=false){
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
        $str.="<a class='venobox_".$this->boxID." ".$this->linkCssClass."' ";
        if ($this->type!=0)
            $str.="data-type='".$GLOBALS['TL_CONFIG']['VenoBox']['types'][$outputType]."' ";
        $str.=" href='".$this->href;
        if($this->type==3 || $this->type==6) {
            if (strpos($this->href, "?")===false) {
                $str .= "?";
            } else {
                $str .= "&";
            }
            $str .= "REQUEST_TOKEN=" . REQUEST_TOKEN;
        }
        $str.="' ";
        if($this->gallery)
            $str.="data-gall='venoGallery_".$this->boxID."_".$this->galleryID."' ";
        if(!empty($this->overlayCssClass))
            $str.="data-css='".$this->overlayCssClass."' ";
        if(!empty($this->overlayColor))
            $str.="data-overlay='".$this->overlayColor."' ";
        if(isset($this->description) && !empty($this->description))
            $str.=" title='".$this->description."'";
        $str.=">";
        if(!$justOpenTag)
            $str.=$this->text."</a>";

        return $str;
    }

    function buildHtmlArrtibutes(){
//        var_dump($this);
        $outputType=$this->type;
        if($this->type==6) {
            if(class_exists("PageAjax")) {
                $this->href = \PageAjax::getAjaxURL()."?" . $this->href;
                $outputType = 3;
            }
            else
                return array("0"=>"#","2"=>"title='install page2ajax-extension for this to work'");
        }
        $att="";
        $href="";
        $title="";
        if ($this->type!=0)
            $att.="data-type='".$GLOBALS['TL_CONFIG']['VenoBox']['types'][$outputType]."' ";
        //href=
        $href.=$this->href;
        if($this->type==3 || $this->type==6) {
            if (strpos($this->href, "?")===false) {
                $href.= "?";
            } else {
                $href.= "&";
            }
            $href.= "REQUEST_TOKEN=" . REQUEST_TOKEN;
        }
        if($this->gallery)
            $att.="data-gall='venoGallery_".$this->galleryID."' ";
        if(!empty($this->overlayCssClass))
            $att.="data-css='".$this->overlayCssClass."' ";
        if(!empty($this->overlayColor))
            $att.="data-overlay='".$this->overlayColor."' ";
        $att.="class='venobox_".$this->boxID." ".$this->linkCssClass."' ";
        //title=
        if(isset($this->description) && !empty($this->description))
            $title.=$this->description;


        return array($href,$title,$att);
    }

    private function replaceInsterTagsAjax()
    {
        if (preg_match("/\{\{(([^\{\}])*)\}\}/", $this->href)) {
            if (strpos($this->href, '{{') !== false) {
                $url=substr($this->href,0,strpos($this->href,"{{"));
                $tagTyp=substr($this->href,2,strpos($this->href,"::")-2);
                if($this->type==6)
                    switch($tagTyp) {
                        case"link_url":
                            $url="action=page&g=1&id=";
                            break;
                        case"article":
                            $url="action=art&g=1&id=";
                            break;
                        default:
                            $this->href=preg_replace("/\{\{(([^\{\}])*)\}\}/","",$this->href);
                            return "Insert_Tag_Could_Not_Be_Converted";
                    }
                $this->href= $url. str_replace(array('{{'.$tagTyp.'::', '}}'), '', $this->href);
            }
        }
    }




}
