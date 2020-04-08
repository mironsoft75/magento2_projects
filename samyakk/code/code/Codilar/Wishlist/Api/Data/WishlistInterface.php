<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Wishlist\Api\Data;

use Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface;

interface WishlistInterface extends AbstractResponseDataInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @return int|bool
     */
    public function getShared();

    /**
     * @return string
     */
    public function getSharingCode();

    /**
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface[]
     */
    public function getItems();

    /**
     * @return bool
     */
    public function getHasItems();

    /**
     * @return int
     */
    public function getItemsCount();
}
