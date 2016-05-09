<?php
/**
 * Part of the Joomla Framework Extension Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Extension;

/**
 * Class Extension
 *
 * @package Joomla\Extension
 *
 * @since  1.0
 */
class Extension implements ExtensionInterface
{
	/** @var callable[]  */
	private $listeners = [];

	/**
	 * Get the listeners
	 *
	 * @param   string  $eventName  The name of the event
	 *
	 * @return  callable[]
	 */
	public function getListeners($eventName)
	{
		if (!key_exists($eventName, $this->listeners))
		{
			return [];
		}

		return $this->listeners[$eventName];
	}

	/**
	 * Add a listener
	 *
	 * @param   string   $eventName  The name of the event
	 * @param   callable $listener   The event handler
	 *
	 * @return  void
	 */
	public function addListener($eventName, $listener)
	{
		if (!key_exists($eventName, $this->listeners))
		{
			$this->listeners[$eventName] = [];
		}

		$this->listeners[$eventName][] = $listener;
	}
}
