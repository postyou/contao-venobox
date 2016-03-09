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

Class VenoHelper
{


    public function renderCeText($objElement, $strBuffer)
    {
        //todo build veno element repalce A Tag with buildHTLM form VenoElement
//        $elem = unserialize($objElement->venoList);
        if (isset($objElement->type) && $objElement->type=="text") {
            if ($objElement->fullsize = 2 && isset($objElement->venoList) && !empty($objElement->venoList)) {
                $venoProperties = unserialize($objElement->venoList);
                $elem =$venoProperties[0];
                $vElem = new VenoElement($elem, 1);
                $html= $vElem->buildHtml(true) . "\n";

                self::loadVenoScripts();
                $strBuffer = substr_replace(
                    $strBuffer,
                    VenoBox::getJs($venoProperties,Input::get("venoboxOpen")) . "</div>",
                    strpos($strBuffer, "</div>") - 1
                );
                $a_start   = strpos($strBuffer, "<a href");
                $a_end     = strpos($strBuffer, ">", $a_start) + 1;
                $strBuffer = substr_replace($strBuffer, $html, $a_start, $a_end - $a_start);
            }
        }
            return $strBuffer;
    }


    public static function getVenoBoxID()
    {
        return uniqid('');
    }
    public static function getVenoBoxClass($boxid)
    {
        return "venobox_" . $boxid;
    }
    
    public static function loadVenoScripts()
    {
        $GLOBALS['TL_CSS'][] = "/composer/vendor/nicolafranchini/venobox/venobox/venobox.css";
        $GLOBALS['TL_JAVASCRIPT'][] = "/composer/vendor/nicolafranchini/venobox/venobox/venobox.js";
        $GLOBALS['TL_CSS'][] = 'system/modules/venobox/assets/css/frontend.css';
    }
}
