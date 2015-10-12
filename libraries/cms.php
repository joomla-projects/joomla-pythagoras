<?php
/**
 * @package    Joomla.Libraries
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Set the platform root path as a constant if necessary.
if (!defined('JPATH_PLATFORM'))
{
	define('JPATH_PLATFORM', __DIR__);
}

// Import the library loader if necessary.
if (!class_exists('JLoader'))
{
	require_once JPATH_PLATFORM . '/loader.php';
}

// Make sure that the Joomla Platform has been successfully loaded.
if (!class_exists('JLoader'))
{
	throw new RuntimeException('Joomla Platform not loaded.');
}

// Register the library base path for CMS libraries.
JLoader::registerPrefix('J', JPATH_PLATFORM . '/cms', false, true);

// Create the Composer autoloader
/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require JPATH_LIBRARIES . '/vendor/autoload.php';
$loader->unregister();

// Decorate Composer autoloader
spl_autoload_register(array(new JClassLoader($loader), 'loadClass'), true, true);

// Allow classes of the Joomla namespace to also be loaded off the libraries/joomla folder
$loader->addPsr4('Joomla\\', JPATH_LIBRARIES . '/joomla');

// Allow classes of the Joomla\Cms namespace to also be loaded from the libraries/cms folder
$loader->addPsr4('Joomla\\Cms\\', JPATH_LIBRARIES . '/cms');

// Register the class aliases for Framework classes that have replaced their Platform equivilents
require_once JPATH_LIBRARIES . '/classmap.php';

// Ensure FOF autoloader included - needed for things like content versioning where we need to get an FOFTable Instance
if (!class_exists('FOFAutoloaderFof'))
{
	include_once JPATH_LIBRARIES . '/fof/include.php';
}

// Register a handler for uncaught exceptions that shows a pretty error page when possible
set_exception_handler(array('JErrorPage', 'render'));

// Define the Joomla version if not already defined.
if (!defined('JVERSION'))
{
	$jversion = new JVersion;
	define('JVERSION', $jversion->getShortVersion());
}

// Set up the message queue logger for web requests
if (array_key_exists('REQUEST_METHOD', $_SERVER))
{
	JLog::addLogger(array('logger' => 'messagequeue'), JLog::ALL, array('jerror'));
}

// Register JArrayHelper due to JRegistry moved to composer's vendor folder
JLoader::register('JArrayHelper', JPATH_PLATFORM . '/joomla/utilities/arrayhelper.php');
