<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Model\Data\CategoryPage\Details;

class AdditionalAttributes extends \Magento\Framework\DataObject implements \Codilar\CategoryApi\Api\Data\CategoryPage\Details\AdditionalAttributesInterface
{
	/**
	 * @return integer
	 */
	public function getAttributeId()
	{
		return $this->getData('attribute_id');
	}


	/**
	 * @param integer $attributeId
	 * @return $this
	 */
	public function setAttributeId($attributeId)
	{
		return $this->setData('attribute_id', $attributeId);
	}


	/**
	 * @return integer
	 */
	public function getAttributeValue()
	{
		return $this->getData('attribute_value');
	}


	/**
	 * @param integer $attributeValue
	 * @return $this
	 */
	public function setAttributeValue($attributeValue)
	{
		return $this->setData('attribute_value', $attributeValue);
	}


	/**
	 * @return string
	 */
	public function getAttributeName()
	{
		return $this->getData('attribute_name');
	}


	/**
	 * @param string $attributeName
	 * @return $this
	 */
	public function setAttributeName($attributeName)
	{
		return $this->setData('attribute_name', $attributeName);
	}


	/**
	 * @return string
	 */
	public function getAttributeValueLabel()
	{
		return $this->getData('attribute_value_label');
	}


	/**
	 * @param string $attributeValueLabel
	 * @return $this
	 */
	public function setAttributeValueLabel($attributeValueLabel)
	{
		return $this->setData('attribute_value_label', $attributeValueLabel);
	}
}
