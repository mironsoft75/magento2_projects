<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Model\Data\Product;

class CustomOptions extends \Magento\Framework\DataObject implements \Codilar\Product\Api\Data\Product\CustomOptionsInterface
{
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
	public function getRequired()
	{
		return $this->getData('required');
	}


	/**
	 * @param boolean $required
	 * @return $this
	 */
	public function setRequired($required)
	{
		return $this->setData('required', $required);
	}


	/**
	 * @return \Codilar\Product\Api\Data\Product\CustomOptions\ValuesInterface[]
	 */
	public function getValues()
	{
		return $this->getData('values');
	}


	/**
	 * @param \Codilar\Product\Api\Data\Product\CustomOptions\ValuesInterface[] $values
	 * @return $this
	 */
	public function setValues($values)
	{
		return $this->setData('values', $values);
	}
}
