<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Checkout\Api\Data;

interface ShippingMethodInterface
{
	/**
	 * @return integer
	 */
	public function getAmount();


	/**
	 * @param integer $amount
	 * @return $this
	 */
	public function setAmount($amount);


	/**
	 * @return string
	 */
	public function getCarrierCode();


	/**
	 * @param string $carrierCode
	 * @return $this
	 */
	public function setCarrierCode($carrierCode);


	/**
	 * @return string
	 */
	public function getCarrierTitle();


	/**
	 * @param string $carrierTitle
	 * @return $this
	 */
	public function setCarrierTitle($carrierTitle);


	/**
	 * @return string
	 */
	public function getMethodCode();


	/**
	 * @param string $methodCode
	 * @return $this
	 */
	public function setMethodCode($methodCode);


	/**
	 * @return string
	 */
	public function getMethodTitle();


	/**
	 * @param string $methodTitle
	 * @return $this
	 */
	public function setMethodTitle($methodTitle);
}
