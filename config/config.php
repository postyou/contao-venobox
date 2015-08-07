<?php

if (!defined('TL_ROOT')) die('You cannot access this file directly!');
$GLOBALS['TL_CTE']['media']['VenoBox'] = 'VenoBox';

$GLOBALS['BE_FFL']['venoBoxWizard'] = 'VenoBoxWizard';

$GLOBALS['TL_CONFIG']['VenoBox']['types']=array("image","iframe","inline","ajax","youtube","vimeo","page2ajax") ;

//if(TL_MODE == 'FE') {
//    $GLOBALS['TL_CSS'][] = "/system/modules/venobox/assets/venobox/venobox.css";
//    $GLOBALS['TL_JAVASCRIPT'][] = "/system/modules/venobox/assets/venobox/venobox.js";
//    $GLOBALS['TL_CSS'][] = 'system/modules/venobox/assets/css/frontend.css';
//
//}

$GLOBALS['TL_HOOKS']['getContentElement'][] = array('postyou\VenoHelper', 'renderCeText');


?>
