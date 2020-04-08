<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\MegaMenu\Model;

use Codilar\MegaMenu\Api\Data\CategoryInterface;
use Magento\Catalog\Model\Category as Subject;
use Magento\Framework\DataObject;

class Category extends Subject implements CategoryInterface
{

    /**
     * @param \Codilar\MegaMenu\Api\Data\CategoryInterface[] $children
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface|DataObject
     */
    public function setChildren($children)
    {
        return $this->setData("children", $children);
    }

    /**
     * @param string $imageUrl
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface|DataObject
     */
    public function setImageUrl($imageUrl)
    {
        return $this->setData("image_url", $imageUrl);
    }

    /**
     * @param string $urlKey
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface|DataObject
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData("url_key", $urlKey);
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->getUrlKey();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getData('description');
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setData('description', $description);
    }

    /**
     * @param string $slug
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface|DataObject
     */
    public function setSlug($slug)
    {
        return $this->setData("slug", $slug);
    }

    /**
     * @return boolean
     */
    public function getIsStatic()
    {
        return $this->getData("is_static");
    }

    /**
     * @param boolean $isStatic
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface|DataObject
     */
    public function setIsStatic($isStatic)
    {
        return $this->setData("is_static", $isStatic);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMenuImage()
    {
        return $this->getImageUrl("home_icon");
    }

    /**
     * @param string $menuImage
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface|DataObject
     */
    public function setMenuImage($menuImage)
    {
        return $this->setData("menu_image", $menuImage);
    }

    /**
     * @return boolean
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getIsTagalys()
    {
        return $this->getData("is_tagalys");
    }

    /**
     * @param boolean $isTagalys
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface|DataObject
     */
    public function setIsTagalys($isTagalys)
    {
        return $this->setData("is_tagalys", $isTagalys);
    }
}
