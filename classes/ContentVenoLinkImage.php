<?php
/**
 * Created by PhpStorm.
 * User: Gerald
 * Date: 28.04.2015
 * Time: 14:48
 */

namespace postyou;


use Contao\ContentImage;

class ContentVenoLinkImage extends ContentImage
{

    protected $strTemplate = 'ce_veno_image';
    protected $boxID;

    protected function compile()
    {
        parent::compile();
        $this->boxID = uniqid('');
        if (isset($this->venoList) && !empty($this->venoList)) {

            $config = $this->getArrtibutes($this->venoList);
//            var_dump($config);
            $this->Template->href = $config[0];
            $this->Template->linkTitle = $config[1];
            $this->Template->attributes = $config[2];
            $this->Template->boxClass = "venobox_" . $this->boxID;
            $this->Template->venobox=true;
        }
    }


    private function getArrtibutes($vList)
    {


        $elem = new VenoElement(unserialize($this->venoList)[0],$this->boxID,1);
        return $elem->buildHtmlArrtibutes();
    }

}
