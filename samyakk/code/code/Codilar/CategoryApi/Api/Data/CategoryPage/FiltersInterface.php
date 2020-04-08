<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Api\Data\CategoryPage;

interface FiltersInterface
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
	public function getName();


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name);


	/**
	 * @return string
	 */
	public function getType();


	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type);


	/**
	 * @return \Codilar\CategoryApi\Api\Data\CategoryPage\Filters\ItemsInterface[]
	 */
	public function getItems();


	/**
	 * @param \Codilar\CategoryApi\Api\Data\CategoryPage\Filters\ItemsInterface[] $items
	 * @return $this
	 */
	public function setItems($items);
}
