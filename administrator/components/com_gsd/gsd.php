<?php

/**
 * @package         Google Structured Data
 * @version         4.6.0 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

// Load Framework
if (!@include_once(JPATH_PLUGINS . '/system/nrframework/autoload.php'))
{
	throw new RuntimeException('Novarain Framework is not installed', 500);
}

$app = JFactory::getApplication();

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_gsd'))
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
	return;
}

use NRFramework\Functions;
use NRFramework\Extension;

if (version_compare(JVERSION, '4.0', 'ge'))
{
	define('J4', true);
}

// Load framework's and component's language files
Functions::loadLanguage();
Functions::loadLanguage('plg_system_gsd');	

// Initialize component's library
require_once JPATH_ADMINISTRATOR . '/components/com_gsd/autoload.php';

// Check required extensions
if (!Extension::pluginIsEnabled('nrframework'))
{
	$app->enqueueMessage(JText::sprintf('NR_EXTENSION_REQUIRED', JText::_('GSD'), JText::_('PLG_SYSTEM_NRFRAMEWORK')), 'error');
}

if (!Extension::pluginIsEnabled('gsd'))
{
	$app->enqueueMessage(JText::sprintf('NR_EXTENSION_REQUIRED', JText::_('GSD'), JText::_('PLG_SYSTEM_GSD')), 'error');
}

// Load media files
JHtml::_('jquery.framework');
JHtml::script('com_gsd/script.js', ['relative' => true, 'version' => 'auto']);
JHtml::stylesheet('com_gsd/styles.css', ['relative' => true, 'version' => 'auto']);

GSD\Helper::event('onGSDGetNames');

if ($app->input->get('test'))
{
	$mgrt = new GSD\Migrator('4.0.0');
	$mgrt->run();
}

// Perform the Request task
$controller = JControllerLegacy::getInstance('GSD');
$controller->execute($app->input->get('task'));
$controller->redirect();