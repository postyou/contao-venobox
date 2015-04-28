<?php if (!defined('TL_ROOT')) {
    die('You cannot access this file directly!');
}

// CSS for layout of file-field
if (TL_MODE == 'BE')
    $GLOBALS['TL_CSS'][] = 'system/modules/venobox/assets/css/backend.css|screen';

$GLOBALS['TL_DCA']['tl_content']['palettes']['VenoBox'] =
    '{type_legend},type,headline,headlineOptn;{venobox_list_legend},venoList;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['venoList'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['listitems'],
    'exclude'                 => true,
    'inputType'               => 'venoBoxWizard',
    'eval'                    => array('tl_class' => 'clr'),
    'sql'                     => "blob NULL"
);
