<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Event
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Event\Table;

defined('JPATH_PLATFORM') or die;

use BadMethodCallException;
use JTableInterface;

/**
 * Event class for JTable's onSetNewTags event
 *
 * TODO This is only used in JModelAdmin::batchTag. We need to remove it but we can't use
 * JTable::save as we don't want the table data to be saved. Maybe trigger the onBeforeStore
 * event instead?
 *
 * @since  4.0
 */
class SetNewTagsEvent extends AbstractEvent
{
	/**
	 * Constructor.
	 *
	 * Mandatory arguments:
	 * subject		JTableInterface	The table we are operating on
	 * newTags 		int[]			New tags to be added to or replace current tags for an item
	 * replaceTags	bool			Replace tags (true) or add them (false)
	 *
	 * @param   string  $name       The event name.
	 * @param   array   $arguments  The event arguments.
	 *
	 * @throws  BadMethodCallException
	 */
	public function __construct($name, array $arguments = array())
	{
		if (!array_key_exists('newTags', $arguments))
		{
			throw new BadMethodCallException("Argument 'newTags' is required for event $name");
		}

		if (!array_key_exists('replaceTags', $arguments))
		{
			throw new BadMethodCallException("Argument 'replaceTags' is required for event $name");
		}

		parent::__construct($name, $arguments);
	}

	/**
	 * Setter for the replaceTags attribute
	 *
	 * @param   mixed  $value  The value to set
	 *
	 * @return  boolean  Normalised value
	 */
	protected function setReplaceTags($value)
	{
		return $value ? true : false;
	}
}
