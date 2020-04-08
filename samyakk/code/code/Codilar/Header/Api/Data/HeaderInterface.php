<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Header\Api\Data;


interface HeaderInterface
{
    /**
     * @return \Codilar\Logo\Api\Data\LogoInterface
     */
    public function getLogo();

    /**
     * @param \Codilar\Logo\Api\Data\LogoInterface $logo
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function setLogo($logo);

    /**
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface[]
     */
    public function getMegaMenu();

    /**
     * @param \Codilar\MegaMenu\Api\Data\CategoryInterface[] $megaMenu
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function setMegaMenu($megaMenu);

    /**
     * @return \Codilar\Wishlist\Api\Data\WishlistInterface
     */
    public function getWishlist();

    /**
     * @param \Codilar\Wishlist\Api\Data\WishlistInterface
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function setWishlist($wishlist);

    /**
     * @return \Codilar\Store\Api\Data\StoreInterface[]
     */
    public function getStores();

    /**
     * @param \Codilar\Store\Api\Data\StoreInterface[] $stores
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function setStores($stores);

    /**
     * @return boolean
     */
    public function getisTagalysSearch();


    /**
     * @param boolean $isTagalysSearch
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function setisTagalysSearch($isTagalysSearch);


}
