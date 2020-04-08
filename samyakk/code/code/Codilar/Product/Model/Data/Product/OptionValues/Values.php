<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Model\Data\Product\OptionValues;

class Values extends \Magento\Framework\DataObject implements \Codilar\Product\Api\Data\Product\OptionValues\ValuesInterface
{
	/**
	 * @return integer
	 */
	public function getValueId()
	{
		return $this->getData('value_id');
	}


	/**
	 * @param integer $valueId
	 * @return $this
	 */
	public function setValueId($valueId)
	{
		return $this->setData('value_id', $valueId);
	}


	/**
	 * @return integer
	 */
	public function getValueType()
	{
		return $this->getData('value_type');
	}


	/**
	 * @param integer $valueType
	 * @return $this
	 */
	public function setValueType($valueType)
	{
		return $this->setData('value_type', $valueType);
	}


	/**
	 * @return string
	 */
	public function getValueLabel()
	{
		return $this->getData('value_label');
	}


	/**
	 * @param string $valueLabel
	 * @return $this
	 */
	public function setValueLabel($valueLabel)
	{
		return $this->setData('value_label', $valueLabel);
	}


	/**
	 * @return string
	 */
	public function getValueTitle()
	{
		return $this->getData('value_title');
	}


	/**
	 * @param string $valueTitle
	 * @return $this
	 */
	public function setValueTitle($valueTitle)
	{
		return $this->setData('value_title', $valueTitle);
	}
}
