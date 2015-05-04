<?php if (!defined('TL_ROOT')) {
    die('You cannot access this file directly!');
}



$GLOBALS['TL_DCA']['tl_content']['palettes']['VenoBox'] =
    '{type_legend},type,headline,headlineOptn;{venobox_list_legend},venoList;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['venoList'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['venoList'],
    'exclude'                 => true,
    'inputType'               => 'venoBoxWizard',
    'eval'                    => array('tl_class' => 'clr'),
    'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fullsize']['default'] = 0;
$GLOBALS['TL_DCA']['tl_content']['fields']['fullsize']['inputType'] = 'radio';
$GLOBALS['TL_DCA']['tl_content']['fields']['fullsize']['options'] = array(0,1);
$GLOBALS['TL_DCA']['tl_content']['fields']['fullsize']['reference'] = &$GLOBALS['TL_LANG']['tl_content'];
$GLOBALS['TL_DCA']['tl_content']['fields']['fullsize']['eval'] = array('submitOnChange'=>true,'mandatory'=>true,'tl_class'=>'clr');


$GLOBALS['TL_DCA']['tl_content']['palettes']['image']=str_replace(',imageUrl,fullsize','', $GLOBALS['TL_DCA']['tl_content']['palettes']['image']);
$GLOBALS['TL_DCA']['tl_content']['palettes']['image']=str_replace('{template_legend','{veno_legend},fullsize;{template_legend', $GLOBALS['TL_DCA']['tl_content']['palettes']['image']);

// add Selector
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'fullsize';

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['fullsize_0'] = 'imageUrl';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['fullsize_1'] = 'venoList';


$GLOBALS['TL_CTE']['media']['image'] = 'ContentVenoLinkImage';

