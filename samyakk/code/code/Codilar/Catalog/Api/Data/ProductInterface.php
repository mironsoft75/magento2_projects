<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Catalog\Api\Data;


interface ProductInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getSku();

    /**
     * @param string $sku
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setSku($sku);

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @param float $price
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setPrice($price);

    /**
     * @return float
     */
    public function getFinalPrice();

    /**
     * @param string $finalPrice
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setFinalPrice($finalPrice);

    /**
     * @return string|null
     */
    public function getTypeId();

    /**
     * @param string $typeId
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setTypeId($typeId);

    /**
     * @return string
     */
    public function getUrlKey();

    /**
     * @param string $urlKey
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setUrlKey($urlKey);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @param string $updatedAt
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * @return float
     */
    public function getWeight();

    /**
     * @param float $weight
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setWeight($weight);

    /**
     * @return \Codilar\Catalog\Api\Data\Product\ImageInterface
     */
    public function getImageUrl();

    /**
     * @param \Codilar\Catalog\Api\Data\Product\ImageInterface $imageUrl
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setImageUrl($imageUrl);

    /**
     * @return string
     */
    public function getImage();


    /**
     * @param string $image
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setImage($image);

    /**
     * @return int
     */
    public function getAttributeSetId();

    /**
     * @param int $attributeSetId
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setAttributeSetId($attributeSetId);

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $slug
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setSlug($slug);
    /**
     * @param \Codilar\Catalog\Model\Product $product
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function load($product);

    /**
     * @param int $productId
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function loadById($productId);

    /**
     * @return string
     */
    public function getSpecialPrice();

    /**
     * @param string $price
     * @return $this
     */
    public function setSpecialPrie($price);

    /**
     * @return string
     */
    public function getShipTime();

    /**
     * @param string $shipTime
     * @return $this
     */
    public function setShipTime($shipTime);

    /**
     * @return string
     */
    public function getOfferPercentage();

    /**
     * @param string $offerPercentage
     * @return $this
     */
    public function setOfferPercentage($offerPercentage);

    /**
     * @return boolean
     */
    public function getIsAddable();

    /**
     * @param boolean $isAddable
     * @return mixed
     */
    public function setIsAddable($isAddable);
}
