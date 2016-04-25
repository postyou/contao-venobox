<?php

if (!defined('TL_ROOT')) die('You cannot access this file directly!');
$GLOBALS['TL_CTE']['media']['VenoBox'] = 'VenoBox';

$GLOBALS['BE_FFL']['venoBoxWizard'] = 'VenoBoxWizard';

$GLOBALS['TL_CONFIG']['VenoBox']['types'][0]="image";
$GLOBALS['TL_CONFIG']['VenoBox']['types'][1]="iframe";
$GLOBALS['TL_CONFIG']['VenoBox']['types'][2]="inline";
$GLOBALS['TL_CONFIG']['VenoBox']['types'][3]="ajax";
$GLOBALS['TL_CONFIG']['VenoBox']['types'][4]="youtube";
$GLOBALS['TL_CONFIG']['VenoBox']['types'][5]="vimeo";

$GLOBALS['TL_CONFIG']['VenoBoxWizard']['fields']=array(
    "type"=>0,
    "href"=>1,
    "scripts"=>2,
    "desc"=>3,
    "text"=>4,
    "overlayColor"=>5,
    "id"=>6
);

$GLOBALS['TL_HOOKS']['getContentElement'][] = array('postyou\VenoElement', 'renderCeText');


?>
