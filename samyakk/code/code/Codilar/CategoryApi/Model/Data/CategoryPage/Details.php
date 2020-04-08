<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Model\Data\CategoryPage;

class Details extends \Magento\Framework\DataObject implements \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface
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
     * @return string
     */
    public function getPrice()
    {
        return $this->getData('price');
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice($price)
    {
        return $this->setData('price', $price);
    }

    /**
     * @return string
     */
    public function getSpecialPrice()
    {
        return $this->getData('special_price');
    }

    /**
     * @param string $specialPrice
     * @return $this
     */
    public function setSpecialPrice($specialPrice)
    {
        return $this->setData('special_price', $specialPrice);
    }

    /**
     * @return string
     */
    public function getDiscountPercentage()
    {
        return $this->getData('discount_percentage');
    }

    /**
     * @param string $discountPercentage
     * @return $this
     */
    public function setDiscountPercentage($discountPercentage)
    {
        return $this->setData('discount_percentage', $discountPercentage);
    }

    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterface[]
     */
    public function getImages()
    {
        return $this->getData('images');
    }

    /**
     * @param \Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterface[] $image
     * @return $this
     */
    public function setImages($images)
    {
        return $this->setData('images', $images);
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->getData('link');
    }

    /**
     * @param string $link
     * @return $this
     */
    public function setLink($link)
    {
        return $this->setData('link', $link);
    }

    /**
     * @return boolean
     */
    public function getInStock()
    {
        return $this->getData('in_stock');
    }

    /**
     * @param boolean $inStock
     * @return $this
     */
    public function setInStock($inStock)
    {
        return $this->setData('in_stock', $inStock);
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
    public function getProductTag()
    {
        return $this->getData('product_tag');
    }

    /**
     * @param string $productTag
     * @return $this
     */
    public function setProductTag($productTag)
    {
        return $this->setData('product_tag', $productTag);
    }
    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\Details\AdditionalAttributesInterface[]
     */
    public function getAdditionalAttributes()
    {
        return $this->getData('additional_attributes');
    }

    /**
     * @param \Codilar\CategoryApi\Api\Data\CategoryPage\Details\AdditionalAttributesInterface[] $additionalAttributes
     * @return $this
     */
    public function setAdditionalAttributes($additionalAttributes)
    {
        return $this->setData('additional_attributes', $additionalAttributes);
    }

    /**
     * @return string
     */
    public function getUrlKey()
    {
        return $this->getData('url_key');
    }

    /**
     * @param string $urlKey
     * @return $this
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData('url_key', $urlKey);
    }
}
