<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Service
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Cms\Provider;

defined('JPATH_PLATFORM') or die;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * The CMS application service provider.
 *
 * @since  4.0
 */
class ApplicationProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$container->set('JApplicationSite', array($this, 'getApplicationSite'));
		$container->set('JApplicationAdministrator', array($this, 'getApplicationAdministrator'));
		$container->set('InstallationApplicationWeb', array($this, 'getApplicationInstallation'));
	}

	/**
	 * Creates a JApplicationSite object;
	 *
	 * @param Container $container
	 *
	 * @return JApplicationSite
	 */
	public function getApplicationSite(Container $container)
	{
		return $this->loadApplication(
				new \JApplicationSite($container->get('input'), $container->get('config')),
				$container);
	}

	/**
	 * Creates a JApplicationAdministrator object;
	 *
	 * @param Container $container
	 *
	 * @return JApplicationAdministrator
	 */
	public function getApplicationAdministrator(Container $container)
	{
		return $this->loadApplication(
				new \JApplicationAdministrator($container->get('input'), $container->get('config')),
				$container);
	}

	/**
	 * Creates a InstallationApplicationWeb object;
	 *
	 * @param Container $container
	 *
	 * @return InstallationApplicationWeb
	 */
	public function getApplicationInstallation(Container $container)
	{
		return $this->loadApplication(
				new \InstallationApplicationWeb($container->get('input'), $container->get('config')),
				$container);
	}

	/**
	 * Injects objects like the language, document or session into the given application
	 * from the given container.
	 *
	 * @param \JApplicationCms $app
	 * @param Container $container
	 *
	 * @return \JApplicationCms
	 */
	private function loadApplication(\JApplicationCms $app, Container $container)
	{
		// TODO this needs to be moved as it is the wrong place to do it here, but
		// JSession and others do need an app at this stage
		\JFactory::$application = $app;
		$container->share('app', $app);

		$app->setContainer($container);
		$app->setLanguage($container->get('language'));
		$app->setDocument($container->get('JDocument'));
		$app->setSession($container->get('JSession'));
		$app->setDispatcher($container->get('Dispatcher'));

		return $app;
	}
}