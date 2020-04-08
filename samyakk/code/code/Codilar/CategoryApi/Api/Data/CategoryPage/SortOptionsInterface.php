<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Api\Data\CategoryPage;

interface SortOptionsInterface
{
	/**
	 * @return string
	 */
	public function getId();


	/**
	 * @param string $id
	 * @return $this
	 */
	public function setId($id);


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
	 * @return \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptions\DirectionsInterface[]
	 */
	public function getDirections();


	/**
	 * @param \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptions\DirectionsInterface[] $directions
	 * @return $this
	 */
	public function setDirections($directions);


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
