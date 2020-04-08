<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Checkout\Model\Data;

class ShippingMethod extends \Magento\Framework\DataObject implements \Codilar\Checkout\Api\Data\ShippingMethodInterface
{
	/**
	 * @return integer
	 */
	public function getAmount()
	{
		return $this->getData('amount');
	}


	/**
	 * @param integer $amount
	 * @return $this
	 */
	public function setAmount($amount)
	{
		return $this->setData('amount', $amount);
	}


	/**
	 * @return string
	 */
	public function getCarrierCode()
	{
		return $this->getData('carrier_code');
	}


	/**
	 * @param string $carrierCode
	 * @return $this
	 */
	public function setCarrierCode($carrierCode)
	{
		return $this->setData('carrier_code', $carrierCode);
	}


	/**
	 * @return string
	 */
	public function getCarrierTitle()
	{
		return $this->getData('carrier_title');
	}


	/**
	 * @param string $carrierTitle
	 * @return $this
	 */
	public function setCarrierTitle($carrierTitle)
	{
		return $this->setData('carrier_title', $carrierTitle);
	}


	/**
	 * @return string
	 */
	public function getMethodCode()
	{
		return $this->getData('method_code');
	}


	/**
	 * @param string $methodCode
	 * @return $this
	 */
	public function setMethodCode($methodCode)
	{
		return $this->setData('method_code', $methodCode);
	}


	/**
	 * @return string
	 */
	public function getMethodTitle()
	{
		return $this->getData('method_title');
	}


	/**
	 * @param string $methodTitle
	 * @return $this
	 */
	public function setMethodTitle($methodTitle)
	{
		return $this->setData('method_title', $methodTitle);
	}
}
