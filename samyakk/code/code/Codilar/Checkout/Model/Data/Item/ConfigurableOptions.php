<?php

/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model\Data\Item;

class ConfigurableOptions extends \Magento\Framework\DataObject implements \Codilar\Checkout\Api\Data\Item\ConfigurableOptionsInterface
{
	/**
	 * @return string
	 */
	public function getAttributeCode()
	{
		return $this->getData('attribute_code');
	}


	/**
	 * @param string $attributeCode
	 * @return $this
	 */
	public function setAttributeCode($attributeCode)
	{
		return $this->setData('attribute_code', $attributeCode);
	}


	/**
	 * @return integer
	 */
	public function getId()
	{
		return $this->getData('id');
	}


	/**
	 * @param integer $id
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
}
