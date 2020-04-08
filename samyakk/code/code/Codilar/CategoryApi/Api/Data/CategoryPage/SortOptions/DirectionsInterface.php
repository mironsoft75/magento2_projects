<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Api\Data\CategoryPage\SortOptions;

interface DirectionsInterface
{
	/**
	 * @return string
	 */
	public function getDirection();


	/**
	 * @param string $direction
	 * @return $this
	 */
	public function setDirection($direction);


	/**
	 * @return string
	 */
	public function getLabel();


	/**
	 * @param string $label
	 * @return $this
	 */
	public function setLabel($label);


	/**
	 * @return boolean
	 */
	public function getSelected();


	/**
	 * @param boolean $selected
	 * @return $this
	 */
	public function setSelected($selected);
}
