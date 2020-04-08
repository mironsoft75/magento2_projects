<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Model\Data\CategoryPage\Filters;

class Items extends \Magento\Framework\DataObject implements \Codilar\CategoryApi\Api\Data\CategoryPage\Filters\ItemsInterface
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
	public function getName()
	{
		return $this->getData('name');
	}


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		return $this->setData('name', $name);
	}


	/**
	 * @return integer
	 */
	public function getCount()
	{
		return $this->getData('count');
	}


	/**
	 * @param integer $count
	 * @return $this
	 */
	public function setCount($count)
	{
		return $this->setData('count', $count);
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
