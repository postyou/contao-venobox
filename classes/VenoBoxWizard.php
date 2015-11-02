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

class VenoBoxWizard extends \Widget
{
    private $fieldNumber = 5;
    /**
     * Submit user input
     * @var boolean
     */
    protected $blnSubmitInput = true;
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_widget';

    /**
     * Add specific attributes
     * @param string
     * @param mixed
     */
    public function __set($strKey, $varValue)
    {
        switch ($strKey) {
            case 'maxlength':
                if ($varValue > 0) {
                    $this->arrAttributes['maxlength'] = $varValue;
                }
                break;
            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }

    /**
     * Generate the widget and return it as string
     * @return string
     */

    public function generate()
    {
        $single=false;
        switch($this->objDca->activeRecord->type){
            case "text":
                $single=true;
                break;
            case "image":
                $single=true;
                break;
        }


        echo "<script>
    /**
     * List wizard
     *
     * @param {object} el The DOM element
     * @param {string} command The command name
     * @param {string} id The ID of the target element
     */
    function myListWizard(el, command, id, name) {
        var list = $(id),
            parent = $(el).getParent('li'),
            items = list.getChildren(),
            tabindex = list.get('data-tabindex'),
            input, previous, next, rows, i, j;
        Backend.getScrollOffset();
        switch (command) {
            case 'copy':
                var clone = parent.clone(true).inject(parent, 'before');
                if (input = parent.getFirst('input')) {
                    var elLength = parent.childNodes.length;
                    for (i = 0; i < elLength; i++) {
                        if (parent.childNodes[i].nodeName == 'INPUT' || parent.childNodes[i].nodeName == 'SELECT') {
                            clone.childNodes[i].value = parent.childNodes[i].value;
                        }
                    }
                }
                break;
            case 'up':
                if (previous = parent.getPrevious('li')) {
                    parent.inject(previous, 'before');
                } else {
                    parent.inject(list, 'bottom');
                }
                break;
            case 'down':
                if (next = parent.getNext('li')) {
                    parent.inject(next, 'after');
                } else {
                    parent.inject(list.getFirst('li'), 'before');
                }
                break;
            case 'delete':
                if (items.length > 1) {
                    parent.destroy();
                } else {
                    lastOne = list.getChildren()[0];
                    child_Length = lastOne.childNodes.length;
                    for (i = 0; i < child_Length; i++) {
                        if (lastOne.childNodes[i].nodeName == 'INPUT')
                            lastOne.childNodes[i].set('value', '');
                    }
                }
                break;
        }
        rows = list.getChildren();
        for (i = 0; i < rows.length; i++) {
            var copybaleChildren = rows[i].getElementsByClassName('copybale');
            var elLength = copybaleChildren.length;
            var textFieldsNumber = 0;
            for (j = 0; j < elLength; j++) {
                if (copybaleChildren[j].nodeName == 'LABEL') {
                    copybaleChildren[j].htmlFor  = name + '[' + i + ']' + '[' + textFieldsNumber + ']';
                }
                if (copybaleChildren[j].nodeName == 'INPUT' || copybaleChildren[j].nodeName == 'SELECT') {
                    copybaleChildren[j].set('tabindex', i + 1);
                    copybaleChildren[j].name = name + '[' + i + ']' + '[' + textFieldsNumber + ']';
                    textFieldsNumber++;
                }


            }
        }
        new Sortables(list, {
            contstrain: true,
            opacity: 0.6,
            handle: '.drag-handle'
        });
    }
</script>";


 $arrButtons = array('copy', 'drag', 'up', 'down', 'delete');
        if($single)
            $arrButtons=array();
//        $arrButtons = array('copy', 'delete');
        $strCommand = 'cmd_' . $this->strField;
// Change the order
        if (\Input::get($strCommand) && is_numeric(\Input::get('cid')) && \Input::get('id') == $this->currentRecord) {
            $this->import('Database');
            switch (\Input::get($strCommand)) {
                case 'copy':
                    $this->varValue = $this->duplicate($this->varValue, \Input::get('cid'));
                    break;
                case 'up':
                    $this->varValue = array_move_up($this->varValue, \Input::get('cid'));
                    break;
                case 'down':
                    $this->varValue = array_move_down($this->varValue, \Input::get('cid'));
                    break;
                case 'delete':
                    $this->varValue = array_delete($this->varValue, \Input::get('cid'));
                    break;
            }
            if (\Input::post('FORM_SUBMIT') == $this->strTable) {
                error_log(preg_replace('/&(amp;)?cid=[^&]*/i', '',
                    preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '',
                        \Environment::get('request'))));
                $this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '',
                    preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '',
                        \Environment::get('request'))));
            }
        }
// Make sure there is at least an empty array
        if (!is_array($this->varValue) || empty($this->varValue)) {
           $initArray=array(0);

            for($i=count($initArray);$i<$this->fieldNumber;$i++){
                $initArray[]='';
            }
           $this->varValue =array($initArray);
        }
// Initialize the tab index
        if (!\Cache::has('tabindex')) {
            \Cache::set('tabindex', 1);
        }
        $tabindex = \Cache::get('tabindex');

        $return = "<div class='ce_venoBoxWizard_wrapper'>";
        $return .= '<ul id="ctrl_' . $this->strId . '" class="ce_venoBoxWizard" data-tabindex="' . $tabindex . '">';
        foreach ($this->varValue as $key => $fieldValue) {
            $return .= "<li><div class='ce_venoBox_field_wrapper'><table cellpadding=''>\n";

                    for ($i = 0; $i < $this->fieldNumber; $i++) {
                        if($i==0)
                            $return .=$this->createDropdownMenuAndLabel($key,$i,$tabindex,$fieldValue[$i]);
//                         elseif($i==1)
//                             $return .=$this->createInputFieldAndLabel($key,$i,"",$tabindex,specialchars($fieldValue[$i]),array(1,2));
//                         elseif($i==4)
//                             $return .=$this->createInputFieldAndLabel($key,$i,"",$tabindex,specialchars($fieldValue[$i]),array(3));
                        else
                            $return .=$this->createInputFieldAndLabel($key,$i,"",$tabindex,specialchars($fieldValue[$i]));

                    }
                    $return .= '</table><div class="ce_venoBox_btn_wrapper">';


                // Add buttons
                foreach ($arrButtons as $button) {
                    $class = ($button == 'up' || $button == 'down') ? ' class="button-move"' : '';
                    if ($button == 'drag') {
                        $return .= \Image::getHtml('drag.gif', '',
                            'class="drag-handle" title="' . sprintf($GLOBALS['TL_LANG']['MSC']['move']) . '" style="top:3px;"');
                        $return .="\n\n";
                    } else {
                    $return .= \Image::getHtml($button . '.gif', $GLOBALS['TL_LANG']['MSC']['lw_' . $button],
                        'class="tl_listwizard_img" onclick="myListWizard(this,\'' . $button . '\',\'ctrl_' . $this->strId . '\',\'' . $this->strId . '\')"');
                }
            }
            $return .= '</div></li>';
            $tabindex++;
        }
// Store the tab index
        \Cache::set('tabindex', $tabindex);
        $return .= "</ul>\n</div>\n";
        return $return ;
    }

    function duplicate($arrStack, $intIndex)
    {
        $arrBuffer = array();
        foreach ($arrStack as $key => $value) {
            if ($key >= $intIndex) {
                $arrBuffer[$key + 1] = $value;
            }
            if ($key <= $intIndex) {
                $arrBuffer[$key] = $value;
            }
        }
        return $arrBuffer;
    }

    function createInputFieldAndLabel($key,$i,$classes,$tabindex,$value,$wizard=array()){
        $name=$this->strId . '[' . $key . '][' . $i . ']';
        $return="<tr>";
        $return.='<td><label for="' .$name. '" class="copybale" class="copybale" title="'.$GLOBALS['TL_LANG']['tl_content']['venoBoxColumn'.$i][1].'"
        >'.$GLOBALS['TL_LANG']['tl_content']['venoBoxColumn'.$i][0].'</label></td>';
        $return .= '<td><input ';
        $return .= 'type="text" ';
        $return .= 'id="ctrl_'.$name.'" ';
        $return .= 'name="'. $name. '" class="copybale '.$classes.'"';
        $return .= 'tabindex="' . $tabindex . '" value="'.$value.'"' .  $this->getAttributes()  . '/>';
        if(!empty($wizard)) {
            if(!is_array($wizard))
                $wizard=array($wizard);
            if(in_array(1,$wizard))
                $return .= $this->createPageWizard($key, $i, $value);
            if(in_array(2,$wizard))
                $return .= $this->createImageFileWizard($key, $i, $value);
            if(in_array(3,$wizard))
                $return .= $this->createColorPicker($key, $i, $value);
        }
        $return.= '</td>';

        $return.="</tr>";
        return $return;
    }
    function createCheckboxAndLabel($key,$i,$tabindex,$value){
        $return="<tr>";
        $return.='<td><label for="' .$this->strId . '[' . $key . '][' . $i . ']'. '" class="copybale" class="copybale" title="'.$GLOBALS['TL_LANG']['tl_content']['venoBoxColumn'.$i][1].'"
        >'.$GLOBALS['TL_LANG']['tl_content']['venoBoxColumn'.$i][0].'</label></td>';
        $return .= '<td><input type="checkbox" name="' . $this->strId . '[' . $key . '][' . $i . ']'.'" class="copybale"';
        if($value=='1')
            $return .=" checked='checked'";
        $return .= ' tabindex="' . $tabindex . '" value="1"' .  $this->getAttributes() . '/></td>';

        $return.="</tr>";
        return $return;
    }
    function createDropdownMenuAndLabel($key,$i,$tabindex,$value){
        $return="<tr>";
        $return.='<td><label for="' .$this->strId . '[' . $key . '][' . $i . ']'. '" class="copybale" title="'.$GLOBALS['TL_LANG']['tl_content']['venoBoxColumn'.$i][1].'"
		>'.$GLOBALS['TL_LANG']['tl_content']['venoBoxColumn'.$i][0].'</label></td>';
        $return .= '<td><select name="' . $this->strId . '[' . $key . '][' . $i . ']'.'" tabindex="'.$tabindex.$this->getAttributes().'" class="copybale mySelect tl_short">';
        foreach ($GLOBALS['TL_CONFIG']['VenoBox']['types'] as $innerKey => $innerFieldValue) {
            $return .= "<option value='".$innerKey."'";
            if($innerKey==$value)
                $return.=" selected";
            $return.=">".$innerFieldValue."</option>\n";
        }
        $return .= "</select></td></tr>";
        return $return;
    }

    function createPageWizard($key,$i,$value){
        $label="PageTree";
        $name=$this->strId . '[' . $key . '][' . $i . ']';

        return ' <a href="contao/page.php?do=' . \Input::get('do') . '&amp;table=' . \Input::get('table') . '&amp;field=' . $name . '&amp;value=" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']) . '"
         onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':768,\'title\':\'' . specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])) . '\',\'url\':this.href,\'id\':\'' . $name . '\',\'tag\':\'ctrl_'. $name . '\',\'self\':this});return false">' .
        \Image::getHtml('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
    }
    function createImageFileWizard($key,$i,$value){
        $label="ImageFileTree";
        $name=$this->strId . '[' . $key . '][' . $i . ']';

//        $this->loadDataContainer('tl_content');
//        $GLOBALS['TL_DCA']['tl_content']['fields'][$name]['eval']=array(
//            'filesOnly'=>true, 'extensions'=>\Config::get('validImageTypes'), 'fieldType'=>'radio', 'tl_class'=>'w50 wizard'
//        );

        return ' <a href="contao/file.php?do='.\Input::get('do').'&amp;table='.\Input::get('table').'&amp;field='.$name.'&amp;filter=jpg&amp;value="
         title="'.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MSC']['filepicker'])).'"
          onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':768,\'title\':\''.specialchars($GLOBALS['TL_LANG']['MOD']['files'][0]).'\',\'url\':this.href,\'id\':\''.$name.'\',\'tag\':\'ctrl_'.$name.'\',\'self\':this});return false">' .
        \Image::getHtml('pickfile.gif', $GLOBALS['TL_LANG']['MSC']['filepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';

    }
    function createColorPicker($key,$i,$value)
    {
        $label = "ImageFileTree";
        $name = $this->strId . '[' . $key . '][' . $i . ']';
        $fieldName = "ctrl_" . $name;

        $return = "";

        $return .= '<img src="system/themes/flexible/images/pickcolor.gif" alt="Farbe auswählen" style="vertical-align:top;cursor:pointer" title="" id="moo_' . $name . '" height="21" width="15">';

        $return .= '<script>
        window.addEvent("domready", function() {
            new MooRainbow("moo_' . $name . '", {
                id: "' . $fieldName . '",
        startColor: ((cl = $("' . $fieldName . '").value.hexToRgb(true)) ? cl : [255, 0, 0]),
        imgPath: "assets/mootools/colorpicker/1.4/images/",
        onComplete: function(color) {
                    $("' . $fieldName . '").value = color.hex.replace("#", "");
                }
      });
    });

        </script>';

    return $return;
}



}
