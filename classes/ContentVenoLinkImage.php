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

    public function generate()
    {
        if (!empty($this->customTpl)) {
            $this->strTemplate = $this->customTpl;
        } elseif (version_compare(VERSION, '3.5', '>=')) {
            $this->strTemplate='ce_veno_image_3.5';
        }

        return parent::generate();
    }


    protected function compile()
    {
        parent::compile();
        if ($this->fullsize==2) {
            $venobox=new VenoElement($this->venoList);
            VenoElement::loadVenoScripts();
            $venobox->setTemplateVars4ImageTempl($this->Template);
        }
    }
}
