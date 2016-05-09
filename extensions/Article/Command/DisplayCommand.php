<?php
/**
 * Part of the Joomla! Article Extension Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Extension\Article\Command;

use Joomla\Renderer\RendererInterface;
use Joomla\Service\Command;

/**
 * Display Command
 *
 * @package  Joomla/Extension/Article
 *
 * @since    1.0
 */
class DisplayCommand extends Command
{
	/** @var string The name of the entity to be displayed */
	public $entityName;

	/** @var integer The ID of the entity */
	public $id;

	/** @var RendererInterface The renderer to use for output */
	public $renderer;

	/**
	 * DisplayCommand constructor.
	 *
	 * @param   string            $entityName The name of the entity to be displayed
	 * @param   integer           $id         The ID of the entity
	 * @param   RendererInterface $renderer   The renderer to use for output
	 */
	public function __construct($entityName, $id, $renderer)
	{
		$this->entityName = $entityName;
		$this->id         = $id;
		$this->renderer   = $renderer;

		parent::__construct();
	}
}
