<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Api\Data\Product;

interface CustomOptionsInterface
{
	/**
	 * @return integer
	 */
	public function getId();


	/**
	 * @param integer $id
	 * @return $this
	 */
	public function setId($id);


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
	public function getRequired();


	/**
	 * @param boolean $required
	 * @return $this
	 */
	public function setRequired($required);


	/**
	 * @return \Codilar\Product\Api\Data\Product\CustomOptions\ValuesInterface[]
	 */
	public function getValues();


	/**
	 * @param \Codilar\Product\Api\Data\Product\CustomOptions\ValuesInterface[] $values
	 * @return $this
	 */
	public function setValues($values);
}
