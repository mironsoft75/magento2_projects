<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Api\Data;

interface ProductInterface
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
	public function getSku();


	/**
	 * @param string $sku
	 * @return $this
	 */
	public function setSku($sku);


	/**
	 * @return string
	 */
	public function getType();


	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type);


	/**
	 * @return string
	 */
	public function getName();


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name);


	/**
	 * @return boolean
	 */
	public function getStockStatus();


	/**
	 * @param boolean $stockStatus
	 * @return $this
	 */
	public function setStockStatus($stockStatus);


	/**
	 * @return \Codilar\Product\Api\Data\Product\MetaInterface
	 */
	public function getMeta();


	/**
	 * @param \Codilar\Product\Api\Data\Product\MetaInterface $meta
	 * @return $this
	 */
	public function setMeta($meta);


	/**
	 * @return string
	 */
	public function getShortDescription();


	/**
	 * @param string $shortDescription
	 * @return $this
	 */
	public function setShortDescription($shortDescription);


	/**
	 * @return string
	 */
	public function getLongDescription();


	/**
	 * @param string $longDescription
	 * @return $this
	 */
	public function setLongDescription($longDescription);


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
	 * @return float
	 */
	public function getSpecialPrice();


	/**
	 * @param float $specialPrice
	 * @return $this
	 */
	public function setSpecialPrice($specialPrice);


	/**
	 * @return integer
	 */
	public function getOfferPercent();


	/**
	 * @param integer $offerPercent
	 * @return $this
	 */
	public function setOfferPercent($offerPercent);


	/**
	 * @return \Codilar\Product\Api\Data\Product\MediaGalleryInterface[]
	 */
	public function getMediaGallery();


	/**
	 * @param \Codilar\Product\Api\Data\Product\MediaGalleryInterface[] $mediaGallery
	 * @return $this
	 */
	public function setMediaGallery($mediaGallery);


	/**
	 * @return \Codilar\Product\Api\Data\Product\ConfigurationsInterface[]
	 */
	public function getConfigurations();


	/**
	 * @param \Codilar\Product\Api\Data\Product\ConfigurationsInterface[] $configurations
	 * @return $this
	 */
	public function setConfigurations($configurations);


	/**
	 * @return \Codilar\Product\Api\Data\Product\OptionValuesInterface[]
	 */
	public function getOptionValues();


	/**
	 * @param \Codilar\Product\Api\Data\Product\OptionValuesInterface[] $optionValues
	 * @return $this
	 */
	public function setOptionValues($optionValues);


	/**
	 * @return \Codilar\Product\Api\Data\Product\CustomOptionsInterface[]
	 */
	public function getCustomOptions();


	/**
	 * @param \Codilar\Product\Api\Data\Product\CustomOptionsInterface[] $customOptions
	 * @return $this
	 */
	public function setCustomOptions($customOptions);

    /**
     * @return \Codilar\Product\Api\Data\Product\SpecificationGroupInterface[]
     */
    public function getSpecifications();

    /**
     * @param \Codilar\Product\Api\Data\Product\SpecificationGroupInterface[] $specifications
     * @return $this
     */
    public function setSpecifications($specifications);

    /**
     * @return \Codilar\Product\Api\Data\Product\AttributesDataInterface[]
     */
    public function getCustomAttribute();

    /**
     * @param \Codilar\Product\Api\Data\Product\AttributesDataInterface[] $attributes
     * @return $this
     */
    public function setCustomAttribute($attributes);
}
