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


use Contao\ContentImage;
use Contao\Input;

class ContentVenoLinkImage extends ContentImage
{

    protected $strTemplate = 'ce_veno_image_3.4';
    protected $boxID;

    protected function compile()
    {
        if (version_compare(VERSION, '3.50', '>=')) {
            $this->strTemplate='ce_veno_image_3.5';
        }
        parent::compile();
        $this->boxID = uniqid('');
        if (isset($this->venoList) && !empty($this->venoList)) {

            $venoProperties=unserialize($this->venoList);
            $this->boxID = $venoProperties[0][5];
            VenoHelper::loadVenoScripts();
            $config = $this->getArrtibutes($venoProperties);
            $this->Template->href = $config[0];
            $this->Template->linkTitle = $config[1];
            $this->Template->attributes = $config[2];
            $this->Template->venobox=true;
            $this->Template->jsScript=VenoBox::getJs($venoProperties, Input::get("venoboxOpen"));
        }
    }


    private function getArrtibutes($vList)
    {
        $elem = new VenoElement($vList[0], 1);
        return $elem->buildHtmlArrtibutes();
    }

}
