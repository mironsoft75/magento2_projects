<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Api\Data\CategoryPage\Filters;

interface ItemsInterface
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
	 * @return integer
	 */
	public function getCount();


	/**
	 * @param integer $count
	 * @return $this
	 */
	public function setCount($count);


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
