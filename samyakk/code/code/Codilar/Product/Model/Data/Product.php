<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Model\Data;

use Codilar\Product\Api\Data\ProductInterface;

class Product extends \Magento\Framework\DataObject implements \Codilar\Product\Api\Data\ProductInterface
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


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->getData('type');
	}


	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type)
	{
		return $this->setData('type', $type);
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getData('name');
	}


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		return $this->setData('name', $name);
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
	 * @return \Codilar\Product\Api\Data\Product\MetaInterface
	 */
	public function getMeta()
	{
		return $this->getData('meta');
	}


	/**
	 * @param \Codilar\Product\Api\Data\Product\MetaInterface $meta
	 * @return $this
	 */
	public function setMeta($meta)
	{
		return $this->setData('meta', $meta);
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
	 * @return string[]
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
	 * @return \Codilar\Product\Api\Data\Product\ConfigurationsInterface[]
	 */
	public function getConfigurations()
	{
		return $this->getData('configurations');
	}


	/**
	 * @param \Codilar\Product\Api\Data\Product\ConfigurationsInterface[] $configurations
	 * @return $this
	 */
	public function setConfigurations($configurations)
	{
		return $this->setData('configurations', $configurations);
	}


	/**
	 * @return \Codilar\Product\Api\Data\Product\OptionValuesInterface[]
	 */
	public function getOptionValues()
	{
		return $this->getData('option_values');
	}


	/**
	 * @param \Codilar\Product\Api\Data\Product\OptionValuesInterface[] $optionValues
	 * @return $this
	 */
	public function setOptionValues($optionValues)
	{
		return $this->setData('option_values', $optionValues);
	}


	/**
	 * @return \Codilar\Product\Api\Data\Product\CustomOptionsInterface[]
	 */
	public function getCustomOptions()
	{
		return $this->getData('custom_options');
	}


	/**
	 * @param \Codilar\Product\Api\Data\Product\CustomOptionsInterface[] $customOptions
	 * @return $this
	 */
	public function setCustomOptions($customOptions)
	{
		return $this->setData('custom_options', $customOptions);
	}

    /**
     * @return \Codilar\Product\Api\Data\Product\SpecificationGroupInterface[]
     */
    public function getSpecifications()
    {
        return $this->getData('specifications');
    }

    /**
     * @param \Codilar\Product\Api\Data\Product\SpecificationGroupInterface[] $specifications
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
