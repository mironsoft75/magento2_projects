<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Wishlist\Model;

use Codilar\Wishlist\Api\Data\WishlistItemsInterface;
use Magento\Wishlist\Model\Item;

class WishlistItems extends Item implements WishlistItemsInterface
{


    /**
     * @return int
     */
    public function getWishlistItemId()
    {
        return $this->getData("wishlist_item_id");
    }

    /**
     * @return float
     */
    public function getQty()
    {
        return $this->getData("qty");
    }

    /**
     * @param int $wishlistItemId
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setWishlistItemId($wishlistItemId)
    {
        $this->setData("wishlist_item_id", $wishlistItemId);
        return $this;
    }

    /**
     * @param \Codilar\Catalog\Api\Data\ProductInterface $product
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setProduct($product)
    {
        return $this->getData("product", $product);
    }

    /**
     * @return int
     */
    public function getWishlistId()
    {
        return $this->getData("wishlist_id");
    }

    /**
     * @param int $wishlistId
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setWishlistId($wishlistId)
    {
        return $this->setData("wishlist_id", $wishlistId);
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->getData("store_id");
    }

    /**
     * @param int $storeId
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData("store_id", $storeId);
    }

    /**
     * @return string
     */
    public function getAddedAt()
    {
        return $this->getData("added_at");
    }

    /**
     * @param string $addedAt
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setAddedAt($addedAt)
    {
        return $this->setData("added_at");
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getData("description");
    }

    /**
     * @param string $description
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setDescription($description)
    {
        return $this->setData("description");
    }

    /**
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface[]
     */
    public function getOptions()
    {
        return $this->getData("options");
    }

    /**
     * @param array $options
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setOptions($options)
    {
        return $this->setData("options", $options);
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->getData("product_id");
    }

    /**
     * @param int $productId
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setProductId($productId)
    {
        return $this->setData("product_id", $productId);
    }
    /**
     * @return boolean
     */
    public function getIsInStock()
    {
        return $this->getData('is_in_stock');
    }

    /**
     * @param boolean $isInStock
     * @return $this
     */
    public function setIsInStock($isInStock)
    {
        return $this->setData('is_in_stock', $isInStock);
    }
}
