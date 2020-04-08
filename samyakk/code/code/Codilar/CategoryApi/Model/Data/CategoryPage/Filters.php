<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Model\Data\CategoryPage;

class Filters extends \Magento\Framework\DataObject implements \Codilar\CategoryApi\Api\Data\CategoryPage\FiltersInterface
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
	 * @return string
	 */
	public function getType()
	{
		return $this->getData('type');
	}


	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type)
	{
		return $this->setData('type', $type);
	}


	/**
	 * @return \Codilar\CategoryApi\Api\Data\CategoryPage\Filters\ItemsInterface[]
	 */
	public function getItems()
	{
		return $this->getData('items');
	}


	/**
	 * @param \Codilar\CategoryApi\Api\Data\CategoryPage\Filters\ItemsInterface[] $items
	 * @return $this
	 */
	public function setItems($items)
	{
		return $this->setData('items', $items);
	}
}
