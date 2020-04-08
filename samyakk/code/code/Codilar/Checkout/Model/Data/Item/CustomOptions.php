<?php

/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model\Data\Item;

class CustomOptions extends \Magento\Framework\DataObject implements \Codilar\Checkout\Api\Data\Item\CustomOptionsInterface
{
	/**
	 * @return integer
	 */
	public function getOptionId()
	{
		return $this->getData('option_id');
	}


	/**
	 * @param integer $optionId
	 * @return $this
	 */
	public function setOptionId($optionId)
	{
		return $this->setData('option_id', $optionId);
	}


	/**
	 * @return string[]
	 */
	public function getOptionValue()
	{
		return $this->getData('option_value');
	}


	/**
	 * @param string[] $optionValue
	 * @return $this
	 */
	public function setOptionValue($optionValue)
	{
		return $this->setData('option_value', $optionValue);
	}
}
