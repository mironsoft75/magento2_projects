<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Checkout\Api\Data;

interface QuoteInterface
{
	/**
	 * @return string
	 */
	public function getEmail();


	/**
	 * @param string $email
	 * @return $this
	 */
	public function setEmail($email);


	/**
	 * @return \Codilar\Checkout\Api\Data\Quote\ShippingAddressInterface
	 */
	public function getShippingAddress();


	/**
	 * @param \Codilar\Checkout\Api\Data\Quote\ShippingAddressInterface $shippingAddress
	 * @return $this
	 */
	public function setShippingAddress($shippingAddress);


	/**
	 * @return string
	 */
	public function getShippingMethod();


	/**
	 * @param string $shippingMethod
	 * @return $this
	 */
	public function setShippingMethod($shippingMethod);


	/**
	 * @return \Codilar\Checkout\Api\Data\Quote\BillingAddressInterface
	 */
	public function getBillingAddress();


	/**
	 * @param \Codilar\Checkout\Api\Data\Quote\BillingAddressInterface $billingAddress
	 * @return $this
	 */
	public function setBillingAddress($billingAddress);


	/**
	 * @return string
	 */
	public function getPaymentMethod();


	/**
	 * @param string $paymentMethod
	 * @return $this
	 */
	public function setPaymentMethod($paymentMethod);

    /**
     * @return string
     */
    public function getCurrencyCode();

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrencyCode($currency);

    /**
     * @param string $remoteIp
     * @return $this
     */
    public function setRemoteIp($remoteIp);

    /**
     * @return string
     */
    public function getRemoteIp();
}
