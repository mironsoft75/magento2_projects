<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Header\Model;

use Codilar\Header\Api\Data\HeaderInterface;
use Magento\Framework\DataObject;

class Header extends DataObject implements HeaderInterface
{

    /**
     * @return \Codilar\Logo\Api\Data\LogoInterface
     */
    public function getLogo()
    {
        return $this->getData("logo");
    }

    /**
     * @param \Codilar\Logo\Api\Data\LogoInterface $logo
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function setLogo($logo)
    {
        $this->setData("logo", $logo);
        return $this;
    }

    /**
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface[]
     */
    public function getMegaMenu()
    {
        return $this->getData("mega_menu");
    }

    /**
     * @param \Codilar\MegaMenu\Api\Data\CategoryInterface[] $megaMenu
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function setMegaMenu($megaMenu)
    {
        $this->setData("mega_menu", $megaMenu);
        return $this;
    }

    /**
     * @return \Codilar\Wishlist\Api\Data\WishlistInterface
     */
    public function getWishlist()
    {
        return $this->getData("wishlist");
    }

    /**
     * @param \Codilar\Wishlist\Api\Data\WishlistInterface
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function setWishlist($wishlist)
    {
        $this->setData("wishlist", $wishlist);
        return $this;
    }

    /**
     * @return \Codilar\Store\Api\Data\StoreInterface[]
     */
    public function getStores()
    {
        return $this->getData("stores");
    }

    /**
     * @param \Codilar\Store\Api\Data\StoreInterface[] $stores
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function setStores($stores)
    {
        $this->setData("stores", $stores);
        return $this;
    }

    /**
     * @return boolean
     */
    public function getisTagalysSearch()
    {
        return $this->getData("is_tagalys_search");
    }


    /**
     * @param boolean $isTagalysSearch
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function setisTagalysSearch($isTagalysSearch)
    {
        $this->setData("is_tagalys_search", $isTagalysSearch);
    }
}
