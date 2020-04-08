<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Model\Data\CategoryPage;

class SortOptions extends \Magento\Framework\DataObject implements \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptionsInterface
{
	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->getData('id');
	}


	/**
	 * @param string $id
	 * @return $this
	 */
	public function setId($id)
	{
		return $this->setData('id', $id);
	}


	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->getData('Label');
	}


	/**
	 * @param string $label
	 * @return $this
	 */
	public function setLabel($label)
	{
		return $this->setData('Label', $label);
	}


	/**
	 * @return \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptions\DirectionsInterface[]
	 */
	public function getDirections()
	{
		return $this->getData('directions');
	}


	/**
	 * @param \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptions\DirectionsInterface[] $directions
	 * @return $this
	 */
	public function setDirections($directions)
	{
		return $this->setData('directions', $directions);
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
