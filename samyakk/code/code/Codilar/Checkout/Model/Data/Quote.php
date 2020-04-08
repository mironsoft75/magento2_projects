<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Checkout\Model\Data;

use Codilar\Checkout\Api\Data\QuoteInterface;

class Quote extends \Magento\Framework\DataObject implements \Codilar\Checkout\Api\Data\QuoteInterface
{
	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->getData('email');
	}


	/**
	 * @param string $email
	 * @return $this
	 */
	public function setEmail($email)
	{
		return $this->setData('email', $email);
	}


	/**
	 * @return \Codilar\Checkout\Api\Data\Quote\ShippingAddressInterface
	 */
	public function getShippingAddress()
	{
		return $this->getData('shipping_address');
	}


	/**
	 * @param \Codilar\Checkout\Api\Data\Quote\ShippingAddressInterface $shippingAddress
	 * @return $this
	 */
	public function setShippingAddress($shippingAddress)
	{
		return $this->setData('shipping_address', $shippingAddress);
	}


	/**
	 * @return string
	 */
	public function getShippingMethod()
	{
		return $this->getData('shipping_method');
	}


	/**
	 * @param string $shippingMethod
	 * @return $this
	 */
	public function setShippingMethod($shippingMethod)
	{
		return $this->setData('shipping_method', $shippingMethod);
	}


	/**
	 * @return \Codilar\Checkout\Api\Data\Quote\BillingAddressInterface
	 */
	public function getBillingAddress()
	{
		return $this->getData('billing_address');
	}


	/**
	 * @param \Codilar\Checkout\Api\Data\Quote\BillingAddressInterface $billingAddress
	 * @return $this
	 */
	public function setBillingAddress($billingAddress)
	{
		return $this->setData('billing_address', $billingAddress);
	}


	/**
	 * @return string
	 */
	public function getPaymentMethod()
	{
		return $this->getData('payment_method');
	}


	/**
	 * @param string $paymentMethod
	 * @return $this
	 */
	public function setPaymentMethod($paymentMethod)
	{
		return $this->setData('payment_method', $paymentMethod);
	}

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->getData('currency_code');
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrencyCode($currency)
    {
        return $this->setData('currency_code', $currency);
    }

    /**
     * @param string $remoteIp
     * @return $this
     */
    public function setRemoteIp($remoteIp)
    {
        return $this->setData('remote_ip', $remoteIp);
    }

    /**
     * @return string
     */
    public function getRemoteIp()
    {
        return $this->getData('remote_ip');
    }
}
