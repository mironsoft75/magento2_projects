<?php

/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api\Data\Item;

interface CustomOptionsInterface
{
	/**
	 * @return integer
	 */
	public function getOptionId();


	/**
	 * @param integer $optionId
	 * @return $this
	 */
	public function setOptionId($optionId);


	/**
	 * @return string[]
	 */
	public function getOptionValue();


	/**
	 * @param string[] $optionValue
	 * @return $this
	 */
	public function setOptionValue($optionValue);
}
