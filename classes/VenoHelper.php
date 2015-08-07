<?php
/**
 * Created by PhpStorm.
 * User: Gerald
 * Date: 12.06.2015
 * Time: 15:38
 */

namespace postyou;


class VenoHelper {


    public function renderCeText($objElement, $strBuffer){
        $elem = unserialize($objElement->venoList)[0];

        $boxId=self::getVenoBoxID();

        //todo build veno element repalce A Tag with buildHTLM form VenoElement
//        $elem = unserialize($objElement->venoList);

        $vElem = new VenoElement($elem, $boxId, $boxId[1]);
        $html= $vElem->buildHtml(true) . "\n";

        if(isset($objElement->type) && $objElement->type=="text")
            if($objElement->fullsize=2 && isset($objElement->venoList) && !empty($objElement->venoList)) {
                self::loadVenoScripts();
                $strBuffer=substr_replace($strBuffer, VenoBox::getJs($boxId) . "</div>", strpos($strBuffer, "</div>") - 1);
                $a_start=strpos($strBuffer,"<a href");
                $a_end=strpos($strBuffer,">",$a_start)+1;
                $strBuffer=substr_replace($strBuffer,$html,$a_start,$a_end-$a_start);
        }

            return $strBuffer;
    }


    public static function getVenoBoxID(){
        return uniqid('');
    }
    public static function getVenoBoxClass($boxid){
        return "venobox_" . $boxid;
    }
    
    public static function loadVenoScripts(){
        $GLOBALS['TL_CSS'][] = "/composer/vendor/nicolafranchini/venobox/venobox/venobox.css";
        $GLOBALS['TL_JAVASCRIPT'][] = "/composer/vendor/nicolafranchini/venobox/venobox/venobox.js";
        $GLOBALS['TL_CSS'][] = 'system/modules/venobox/assets/css/frontend.css';
    }

}