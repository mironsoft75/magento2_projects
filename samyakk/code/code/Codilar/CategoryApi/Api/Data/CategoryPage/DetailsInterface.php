<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Api\Data\CategoryPage;

interface DetailsInterface
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
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getPrice();

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * @return string
     */
    public function getSpecialPrice();

    /**
     * @param string $specialPrice
     * @return $this
     */
    public function setSpecialPrice($specialPrice);

    /**
     * @return string
     */
    public function getDiscountPercentage();

    /**
     * @param string $discountPercentage
     * @return $this
     */
    public function setDiscountPercentage($discountPercentage);

    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterface[]
     */
    public function getImages();

    /**
     * @param \Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterface[] $images
     * @return $this
     */
    public function setImages($images);

    /**
     * @return string
     */
    public function getLink();

    /**
     * @param string $link
     * @return $this
     */
    public function setLink($link);

    /**
     * @return boolean
     */
    public function getInStock();

    /**
     * @param boolean $inStock
     * @return $this
     */
    public function setInStock($inStock);

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
    public function getProductTag();

    /**
     * @param string $productTag
     * @return $this
     */
    public function setProductTag($productTag);

    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\Details\AdditionalAttributesInterface[]
     */
    public function getAdditionalAttributes();

    /**
     * @param \Codilar\CategoryApi\Api\Data\CategoryPage\Details\AdditionalAttributesInterface[] $additionalAttributes
     * @return $this
     */
    public function setAdditionalAttributes($additionalAttributes);

    /**
     * @return string
     */
    public function getUrlKey();

    /**
     * @param string $urlKey
     * @return $this
     */
    public function setUrlKey($urlKey);
}
