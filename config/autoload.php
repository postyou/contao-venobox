<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package Venobox
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'postYou',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'postYou\VenoElement'   => 'system/modules/venobox/classes/VenoElement.php',
	'postYou\VenoBoxWizard' => 'system/modules/venobox/classes/VenoBoxWizard.php',
	'postYou\VenoBox'       => 'system/modules/venobox/classes/VenoBox.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_venobox' => 'system/modules/venobox/templates',
));
