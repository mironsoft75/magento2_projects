<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Api\Data\Product;

interface ConfigurationsInterface
{
	/**
	 * @return integer
	 */
	public function getProductId();


	/**
	 * @param integer $productId
	 * @return $this
	 */
	public function setProductId($productId);


	/**
	 * @return string
	 */
	public function getProductSku();


	/**
	 * @param string $productSku
	 * @return $this
	 */
	public function setProductSku($productSku);


	/**
	 * @return string
	 */
	public function getProductName();


	/**
	 * @param string $productName
	 * @return $this
	 */
	public function setProductName($productName);


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
	 * @return \Codilar\Product\Api\Data\Product\Configurations\OptionsInterface[]
	 */
	public function getOptions();


	/**
	 * @param \Codilar\Product\Api\Data\Product\Configurations\OptionsInterface[] $options
	 * @return $this
	 */
	public function setOptions($options);


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
     * @return \Codilar\Product\Api\Data\Product\AttributesDataInterface[]
     */
    public function getSpecifications();

    /**
     * @param \Codilar\Product\Api\Data\Product\AttributesDataInterface[] $specifications
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
