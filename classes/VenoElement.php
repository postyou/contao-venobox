<?php
/**
 * Venobox for Contao
 * Extension for Contao Open Source CMS (contao.org)
 *
 * Copyright (c) 2015 POSTYOU
 *
 * @package venobox
 * @author  Gerald Meier
 * @link    http://www.postyou.de
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
namespace postyou;

use Contao\Controller;
use Contao\Environment;
use Contao\Input;

class VenoElement
{

    private $type;
    private $href;
    private $description="";
    private $text="";
    private $overlayColor="";
    private $boxID;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->boxID;
    }

    private $overlayCssClass="";
    private $gallery=1;
    private $linkCssClass="";

    private $galleryID=0;

    public static $autoloadParamName="venoboxOpen";

    public function __construct($initArray=null,$galleryID = 1, $class = "")
    {
        $venoBoxeProperties= VenoGenerator::getConfigArr($initArray);
        $this->setProperties($venoBoxeProperties,$galleryID, $class);
        $this->replaceHrefInsertTagsAjax();
    }

    private function setProperties($initArray,$galleryID = 1, $class = ""){
        $this->type=intval($initArray[0]);
        $this->href=$initArray[1];
        $this->description=$initArray[2];
        $this->text=$initArray[3];
        $this->overlayColor = $initArray[4];
        $this->boxID=$initArray[5];

        $this->linkCssClass=$class;

        if ($this->gallery) {
            $this->galleryID = $galleryID;
        }

        $this->overlayCssClass="veno_".$GLOBALS['TL_CONFIG']['VenoBox']['types'][$this->type];

        if ($this->type == 0) {
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
    }

    public function __toString()
    {
        return $this->buildHtml();
    }

    public function buildHtml($justOpenTag = false)
    {
        if(!$this->check4Page2Ajax())
            return "<p>install <a href=\"https://github.com/postyou/contao-page2ajax/\">".
                   "page2ajax-extension</a> for this to work<p>".($justOpenTag?"<a>":"");
        $str="";
        $str.="<a ";
        $str.=$this->buildAtt()." ";
        $str.=" href='".$this->buildHrefStr()."' ";
        $str .= "title='".$this->buildDescStr()."' ";

        $str.=">";
        if (!$justOpenTag) {
            $str .= $this->text . "</a>";
        }

        return $str;
    }

    private function replaceHrefInsertTagsAjax()
    {
        if (preg_match("/\{\{(([^\{\}])*)\}\}/", $this->href)) {
            if (strpos($this->href, '{{') !== false) {
                $url=substr($this->href, 0, strpos($this->href, "{{"));
                $tagTyp=substr($this->href, 2, strpos($this->href, "::")-2);
                switch ($tagTyp."_".$this->type) {
                    case "link_url_6":
                        $url = "action=page&amp;g=1&amp;id=". str_replace(array('{{'.$tagTyp.'::', '}}'), '', $this->href);
                        break;
                    case "article_url_6":
                        $url = "action=art&amp;g=1&amp;id=". str_replace(array('{{'.$tagTyp.'::', '}}'), '', $this->href);
                        break;
                    case "link_url_1":
                        $url = Environment::get("base").Controller::replaceInsertTags($this->href);
                        break;
                    case "article_url_1":
                        $url = Environment::get("base").Controller::replaceInsertTags($this->href);
                        break;
                        default:
                            $url = "#";
                    }
                    $this->href= $url;
            }
        }
    }

    public function setTemplateVars4ImageTempl($templateObj){

        if(!$this->check4Page2Ajax()) {
            $templateObj->href = false;
            $templateObj->linkTitle ="VenoBox-Error";
        }else {
            $templateObj->href       = $this->buildHrefStr();
            $templateObj->linkTitle  = $this->buildDescStr();
            $templateObj->attributes = $this->buildAtt();
            $templateObj->venobox    = true;
            $templateObj->jsScript   = VenoElement::getJs();
        }
    }

    private function buildDescStr()
    {

        $title = "";
        if (isset($this->description) && !empty($this->description)) {
            $title .= $this->description;
        }
        return $title;
    }

    private function buildAtt(){
        $outputType = $this->type;
        if ($this->type == 6) {
            $outputType = 3;
        }
        $att   = "";
        if ($this->type != 0) {
            $att .= "data-type='" . $GLOBALS['TL_CONFIG']['VenoBox']['types'][$outputType] . "' ";
        }

        if ($this->gallery) {
            $att .= "data-gall='venoGallery_" . $this->galleryID . "' ";
        }
        if (!empty($this->overlayCssClass)) {
            $att .= "data-css='" . $this->overlayCssClass . "' ";
        }
        if (!empty($this->overlayColor)) {
            $att .= "data-overlay='" . $this->overlayColor . "' ";
        }
        $att.="class='venobox_".$this->boxID." ".$this->linkCssClass."' ";
        return $att;
    }

    private function buildHrefStr(){
        $href  = "";
        $href .= $this->href;
        if ($this->type == 3 || $this->type == 6) {
            if (strpos($this->href, "?") === false) {
                $href .= "?";
            } else {
                $href .= "&amp;";
            }
            $href .= "rt=" . REQUEST_TOKEN;
        }
        return $href;
    }

    private function check4Page2Ajax(){
        if ($this->type == 6) {
            if (class_exists("PageAjax")) {
                $this->href = \PageAjax::getAjaxURL()."?" . $this->href;
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    public function getJs()
    {
        if(TL_MODE=="BE")
            return "";

        $autoLoadID=Input::get(self::$autoloadParamName);

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
        $strBuffer.= "$('.".$this->getVenoBoxClass()."').venobox(venoOptions)";
        if (isset($autoLoadID) && !empty($autoLoadID) && $this->boxID==$autoLoadID) {
            $strBuffer .= ".trigger('click');\n";
        } else {
            $strBuffer .= ";\n";
        }
        $strBuffer.="});</script>";
        return $strBuffer;
    }

    public function getVenoBoxClass()
    {
        return "venobox_" . $this->boxID;
    }

    public static function loadVenoScripts()
    {
        if(TL_MODE!="BE") {

            $GLOBALS['TL_CSS'][]        = "/composer/vendor/nicolafranchini/venobox/venobox/venobox.css";
            $GLOBALS['TL_JAVASCRIPT'][] = "/composer/vendor/nicolafranchini/venobox/venobox/venobox.js";
            $GLOBALS['TL_CSS'][]        = 'system/modules/venobox/assets/css/frontend.css';
        }
    }

    public function addClass($classStr){
        if(empty($this->linkCssClass))
            $this->linkCssClass=$classStr;
        else
            $this->linkCssClass.=" ".$classStr;
    }

    public static function renderCeText($objElement, $strBuffer)
    {
        //todo build veno element repalce A Tag with buildHTLM form VenoElement
//        $elem = unserialize($objElement->venoList);
        if (isset($objElement->type) && $objElement->type=="text") {
            if ($objElement->fullsize == 2 && isset($objElement->venoList) && !empty($objElement->venoList)) {
                if(strpos($strBuffer, "<a href")!==false) {
                    $vElem = new VenoElement($objElement->venoList);
                    $html  = $vElem->buildHtml(true) . "\n";
                    self::loadVenoScripts();
                    $strBuffer = substr_replace(
                        $strBuffer,
                        $vElem->getJs() . "</div>",
                        strpos($strBuffer, "</div>") - 1
                    );
                    $a_start   = strpos($strBuffer, "<a href");
                    $a_end     = strpos($strBuffer, ">", $a_start) + 1;
                    $strBuffer = substr_replace($strBuffer, $html, $a_start, $a_end - $a_start);
                }
            }
        }
        return $strBuffer;
    }


}
