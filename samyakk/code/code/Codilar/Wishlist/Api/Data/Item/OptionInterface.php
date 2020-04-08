<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Wishlist\Api\Data\Item;


interface OptionInterface
{
    /**
     * @return int
     */
    public function getOptionId();

    /**
     * @param int $optionId
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface
     */
    public function setOptionId($optionId);

    /**
     * @return int
     */
    public function getWishlistItemId();

    /**
     * @param int $wishlitstItemId
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface
     */
    public function setWishlistItemId($wishlitstItemId);

    /**
     * @return int
     */
    public function getProductId();

    /**
     * @param int $productId
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface
     */
    public function setProductId($productId);

    /**
     * @return string
     */
    public function getCode();

    /**
     * @param string $code
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface
     */
    public function setCode($code);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface
     */
    public function setValue($value);
}
