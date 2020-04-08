<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Model\Data\Product;

class Configurations extends \Magento\Framework\DataObject implements \Codilar\Product\Api\Data\Product\ConfigurationsInterface
{
	/**
	 * @return integer
	 */
	public function getProductId()
	{
		return $this->getData('product_id');
	}


	/**
	 * @param integer $productId
	 * @return $this
	 */
	public function setProductId($productId)
	{
		return $this->setData('product_id', $productId);
	}


	/**
	 * @return string
	 */
	public function getProductSku()
	{
		return $this->getData('product_sku');
	}


	/**
	 * @param string $productSku
	 * @return $this
	 */
	public function setProductSku($productSku)
	{
		return $this->setData('product_sku', $productSku);
	}


	/**
	 * @return string
	 */
	public function getProductName()
	{
		return $this->getData('product_name');
	}


	/**
	 * @param string $productName
	 * @return $this
	 */
	public function setProductName($productName)
	{
		return $this->setData('product_name', $productName);
	}


	/**
	 * @return boolean
	 */
	public function getStockStatus()
	{
		return $this->getData('stock_status');
	}


	/**
	 * @param boolean $stockStatus
	 * @return $this
	 */
	public function setStockStatus($stockStatus)
	{
		return $this->setData('stock_status', $stockStatus);
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
	 * @return float
	 */
	public function getSpecialPrice()
	{
		return $this->getData('special_price');
	}


	/**
	 * @param float $specialPrice
	 * @return $this
	 */
	public function setSpecialPrice($specialPrice)
	{
		return $this->setData('special_price', $specialPrice);
	}


	/**
	 * @return integer
	 */
	public function getOfferPercent()
	{
		return $this->getData('offer_percent');
	}


	/**
	 * @param integer $offerPercent
	 * @return $this
	 */
	public function setOfferPercent($offerPercent)
	{
		return $this->setData('offer_percent', $offerPercent);
	}


	/**
	 * @return \Codilar\Product\Api\Data\Product\MediaGalleryInterface[]
	 */
	public function getMediaGallery()
	{
		return $this->getData('media_gallery');
	}


	/**
	 * @param \Codilar\Product\Api\Data\Product\MediaGalleryInterface[] $mediaGallery
	 * @return $this
	 */
	public function setMediaGallery($mediaGallery)
	{
		return $this->setData('media_gallery', $mediaGallery);
	}


	/**
	 * @return \Codilar\Product\Api\Data\Product\Configurations\OptionsInterface[]
	 */
	public function getOptions()
	{
		return $this->getData('options');
	}


	/**
	 * @param \Codilar\Product\Api\Data\Product\Configurations\OptionsInterface[] $options
	 * @return $this
	 */
	public function setOptions($options)
	{
		return $this->setData('options', $options);
	}


	/**
	 * @return string
	 */
	public function getShortDescription()
	{
		return $this->getData('short_description');
	}


	/**
	 * @param string $shortDescription
	 * @return $this
	 */
	public function setShortDescription($shortDescription)
	{
		return $this->setData('short_description', $shortDescription);
	}


	/**
	 * @return string
	 */
	public function getLongDescription()
	{
		return $this->getData('long_description');
	}


	/**
	 * @param string $longDescription
	 * @return $this
	 */
	public function setLongDescription($longDescription)
	{
		return $this->setData('long_description', $longDescription);
	}

    /**
     * @return \Codilar\Product\Api\Data\Product\AttributesDataInterface[]
     */
    public function getSpecifications()
    {
        return $this->getData('specifications');
    }

    /**
     * @param \Codilar\Product\Api\Data\Product\AttributesDataInterface[] $specifications
     * @return $this
     */
    public function setSpecifications($specifications)
    {
        return $this->setData('specifications', $specifications);
    }

    /**
     * @return \Codilar\Product\Api\Data\Product\AttributesDataInterface[]
     */
    public function getCustomAttribute()
    {
        return $this->getData('custom_attributes');
    }

    /**
     * @param \Codilar\Product\Api\Data\Product\AttributesDataInterface[] $attributes
     * @return $this
     */
    public function setCustomAttribute($attributes)
    {
        return $this->setData('custom_attributes', $attributes);
    }
}
