<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Model\Data\Product\CustomOptions;

class Values extends \Magento\Framework\DataObject implements \Codilar\Product\Api\Data\Product\CustomOptions\ValuesInterface
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
	 * @return float
	 */
	public function getPrice()
	{
		return $this->getData('price');
	}


	/**
	 * @param float $price
	 * @return $this
	 */
	public function setPrice($price)
	{
		return $this->setData('price', $price);
	}


	/**
	 * @return string
	 */
	public function getSku()
	{
		return $this->getData('sku');
	}


	/**
	 * @param string $sku
	 * @return $this
	 */
	public function setSku($sku)
	{
		return $this->setData('sku', $sku);
	}
}
