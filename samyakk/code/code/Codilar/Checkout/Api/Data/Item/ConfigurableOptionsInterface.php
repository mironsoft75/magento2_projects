<?php

/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api\Data\Item;

interface ConfigurableOptionsInterface
{
	/**
	 * @return string
	 */
	public function getAttributeCode();


	/**
	 * @param string $attributeCode
	 * @return $this
	 */
	public function setAttributeCode($attributeCode);


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
	public function getLabel();


	/**
	 * @param string $label
	 * @return $this
	 */
	public function setLabel($label);
}
