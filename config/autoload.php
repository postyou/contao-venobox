<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'postyou',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'postyou\VenoHelper'           => 'system/modules/venobox/classes/VenoHelper.php',
	'postyou\ContentVenoLinkImage' => 'system/modules/venobox/classes/ContentVenoLinkImage.php',
	'postyou\VenoElement'          => 'system/modules/venobox/classes/VenoElement.php',
	'postyou\VenoBoxWizard'        => 'system/modules/venobox/classes/VenoBoxWizard.php',
	'postyou\VenoBox'              => 'system/modules/venobox/classes/VenoBox.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_veno_image_3.5' => 'system/modules/venobox/templates',
	'ce_veno_image_3.4' => 'system/modules/venobox/templates',
	'ce_venobox'    => 'system/modules/venobox/templates',
));
