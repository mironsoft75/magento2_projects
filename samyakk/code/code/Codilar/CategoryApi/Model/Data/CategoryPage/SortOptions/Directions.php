<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Model\Data\CategoryPage\SortOptions;

class Directions extends \Magento\Framework\DataObject implements \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptions\DirectionsInterface
{
	/**
	 * @return string
	 */
	public function getDirection()
	{
		return $this->getData('direction');
	}


	/**
	 * @param string $direction
	 * @return $this
	 */
	public function setDirection($direction)
	{
		return $this->setData('direction', $direction);
	}


	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->getData('label');
	}


	/**
	 * @param string $label
	 * @return $this
	 */
	public function setLabel($label)
	{
		return $this->setData('label', $label);
	}


	/**
	 * @return boolean
	 */
	public function getSelected()
	{
		return $this->getData('selected');
	}


	/**
	 * @param boolean $selected
	 * @return $this
	 */
	public function setSelected($selected)
	{
		return $this->setData('selected', $selected);
	}
}
