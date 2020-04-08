<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Wishlist\Model\Item;

use Codilar\Wishlist\Api\Data\Item\OptionInterface;
use Magento\Wishlist\Model\Item\Option as Subject;

class Option extends Subject implements OptionInterface
{

    /**
     * @return int
     */
    public function getOptionId()
    {
        return $this->getData("option_id");
    }

    /**
     * @param int $optionId
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface
     */
    public function setOptionId($optionId)
    {
        $this->setData("option_id", $optionId);
        return $this;
    }

    /**
     * @return int
     */
    public function getWishlistItemId()
    {
        return $this->getData("wishlist_item_id");
    }

    /**
     * @param int $wishlitstItemId
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface
     */
    public function setWishlistItemId($wishlitstItemId)
    {
        $this->setData("wishlist_item_id", $wishlitstItemId);
        return $this;
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
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface
     */
    public function setProductId($productId)
    {
        $this->setData("product_id", $productId);
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->getData("code");
    }

    /**
     * @param string $code
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface
     */
    public function setCode($code)
    {
        $this->setData("code", $code);
        return $this;
    }

    /**
     * @param string $value
     * @return \Codilar\Wishlist\Api\Data\Item\OptionInterface
     */
    public function setValue($value)
    {
        $this->setData("value");
        return $this;
    }
}
