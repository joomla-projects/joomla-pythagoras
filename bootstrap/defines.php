<?php
/**
 * @package    Joomla.Administrator
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$tmp = explode(DIRECTORY_SEPARATOR, __DIR__);
array_splice($tmp,-1);

define('JPATH_ROOT', implode(DIRECTORY_SEPARATOR, $tmp));

if (JAPPLICATIONTYPE == 'administrator')
{
    define('JPATH_BASE', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator');
}
else
{
    define('JPATH_BASE', JPATH_ROOT);
}

define('JPATH_SITE',          JPATH_ROOT);
define('JPATH_CONFIGURATION', JPATH_ROOT);
define('JPATH_ADMINISTRATOR', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator');
define('JPATH_LIBRARIES',     JPATH_ROOT . DIRECTORY_SEPARATOR . 'libraries');
define('JPATH_PLUGINS',       JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins');
define('JPATH_INSTALLATION',  JPATH_ROOT . DIRECTORY_SEPARATOR . 'installation');
define('JPATH_THEMES',        JPATH_BASE . DIRECTORY_SEPARATOR . 'templates');
define('JPATH_CACHE',         JPATH_BASE . DIRECTORY_SEPARATOR . 'cache');
define('JPATH_MANIFESTS',     JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'manifests');

// Detect the native operating system type.
$os = strtoupper(substr(PHP_OS, 0, 3));

if (!defined('IS_WIN'))
{
    define('IS_WIN', ($os === 'WIN') ? true : false);
}

if (!defined('IS_UNIX'))
{
    define('IS_UNIX', (($os !== 'MAC') && ($os !== 'WIN')) ? true : false);
}
