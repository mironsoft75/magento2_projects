<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Wishlist\Api\Data;

interface WishlistItemsInterface
{
    /**
     * @return int
     */
    public function getWishlistItemId();

    /**
     * @param int $wishlistItemId
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setWishlistItemId($wishlistItemId);

    /**
     * @return int
     */
    public function getWishlistId();

    /**
     * @param int $wishlistId
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setWishlistId($wishlistId);

    /**
     * @return int
     */
    public function getProductId();

    /**
     * @param int $productId
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setProductId($productId);

    /**
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function getProduct();

    /**
     * @param \Codilar\Catalog\Api\Data\ProductInterface $product
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setProduct($product);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param int $storeId
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setStoreId($storeId);

    /**
     * @return string
     */
    public function getAddedAt();

    /**
     * @param string $addedAt
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setAddedAt($addedAt);

    /**
     * @return string|null
     */
    public function getDescription();

    /**
     * @param string $description
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setDescription($description);

    /**
     * @return float
     */
    public function getQty();

    /**
     * @param int $qty
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setQty($qty);

    /**
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface[]
     */
    public function getOptions();

    /**
     * @param array $options
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface
     */
    public function setOptions($options);

    /**
     * @return boolean
     */
    public function getIsInStock();

    /**
     * @param boolean $isInStock
     * @return $this
     */
    public function setIsInStock($isInStock);
}
