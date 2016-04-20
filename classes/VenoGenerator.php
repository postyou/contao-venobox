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

use Contao\Input;

class VenoGenerator
{

    private $boxes=array();

    public function __construct($initArray,$galleryID = 1, $class = "")
    {
        $venoBoxesProperties= self::getConfigArr($initArray,true);
        foreach ($venoBoxesProperties as $key => $propertyArr){
            $this->boxes[$key]= new VenoElement($propertyArr,$galleryID,$class);
        }
    }

    public function buildHtml()
    {
        if(!empty($this->boxes)) {
            reset($this->boxes)->addClass("first");
            end($this->boxes)->addClass("last");
        }
        return implode("\n", $this->boxes);

    }

    public function setTemplateVars($templateObj){

        $templateObj->html=$this->buildHtml();
        $templateObj->js=$this->getJs();
    }

    public function getJs()
    {
        if(TL_MODE=="BE")
            return "";

        $autoLoadID=Input::get(VenoElement::$autoloadParamName);

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
        foreach ($this->boxes as $venobox) {
            $strBuffer.= "$('.".$venobox->getVenoBoxClass()."').venobox(venoOptions)";
            if (isset($autoLoadID) && !empty($autoLoadID) && $venobox->getId()==$autoLoadID) {
                $strBuffer .= ".trigger('click');\n";
            } else {
                $strBuffer .= ";\n";
            }
        }
        $strBuffer.="});</script>";
        return $strBuffer;
    }

    public static function getConfigArr($initArray,$justUnserialse=false){
        if(!is_array($initArray) && is_string($initArray)) {
            $initArray=unserialize($initArray);
            if($justUnserialse)
                return $initArray;
        }

        if (is_array($initArray) && !empty($initArray)) {
            if (is_array($initArray[0])) {
                return $initArray[0];
            }
            return $initArray;
        }

        return null; //TODO ErrorMsg
    }
}
