<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Wishlist\Api;

interface WishlistRepositoryInterface
{
    /**
     * @return \Codilar\Wishlist\Api\Data\WishlistInterface
     */
    public function getWishlist();

    /**
     * @param int $productId
     * @return \Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface
     */
    public function addWishlistItem($productId);

    /**
     * @param int $itemId
     * @return \Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface
     */
    public function removeWishlistItem($itemId);
}
