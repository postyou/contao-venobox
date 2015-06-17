<?php

if (!defined('TL_ROOT')) die('You cannot access this file directly!');
$GLOBALS['TL_CTE']['media']['VenoBox'] = 'VenoBox';

$GLOBALS['BE_FFL']['venoBoxWizard'] = 'VenoBoxWizard';

$GLOBALS['TL_CONFIG']['VenoBox']['types']=array("image","iframe","inline","ajax","youtube","vimeo","page2ajax") ;


if(TL_MODE == 'BE') {
    $GLOBALS['TL_CSS'][] = 'system/modules/venobox/assets/css/backend.css';
    $GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/colorpicker/' . $GLOBALS['TL_ASSETS']['COLORPICKER'] . '/js/mooRainbow.js';
    $GLOBALS['TL_CSS'][] = 'assets/mootools/colorpicker/' . $GLOBALS['TL_ASSETS']['COLORPICKER'] . '/css/mooRainbow.css';

}

if(TL_MODE == 'FE') {
    //$GLOBALS['TL_JAVASCRIPT']['jquery'] = 'assets/jquery/core/' . reset((scandir(TL_ROOT . '/assets/jquery/core', 1))) . '/jquery.js';
    $GLOBALS['TL_CSS'][] = "/system/modules/venobox/assets/venobox/venobox.css";
    $GLOBALS['TL_JAVASCRIPT'][] = "/system/modules/venobox/assets/venobox/venobox.js";
    $GLOBALS['TL_CSS'][] = 'system/modules/venobox/assets/css/frontend.css';

}

$GLOBALS['TL_HOOKS']['getContentElement'][] = array('postyou\VenoHelper', 'renderCeText');


?>
