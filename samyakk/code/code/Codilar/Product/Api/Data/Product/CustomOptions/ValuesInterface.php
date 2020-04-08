<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Api\Data\Product\CustomOptions;

interface ValuesInterface
{
	/**
	 * @return integer
	 */
	public function getId();


	/**
	 * @param integer $id
	 * @return $this
	 */
	public function setId($id);


	/**
	 * @return string
	 */
	public function getLabel();


	/**
	 * @param string $label
	 * @return $this
	 */
	public function setLabel($label);


	/**
	 * @return float
	 */
	public function getPrice();


	/**
	 * @param float $price
	 * @return $this
	 */
	public function setPrice($price);


	/**
	 * @return string
	 */
	public function getSku();


	/**
	 * @param string $sku
	 * @return $this
	 */
	public function setSku($sku);
}
