<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\MegaMenu\Api\Data;

interface CategoryInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setName($name);

    /**
     * @return int
     */
    public function getLevel();

    /**
     * @param int $level
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setLevel($level);

    /**
     * @return string
     */
    public function getUrlKey();

    /**
     * @param string $urlKey
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setUrlKey($urlKey);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $slug
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setSlug($slug);

    /**
     * @return bool
     */
    public function getIncludeInMenu();

    /**
     * @param bool $includeInMenu
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setIncludeInMenu($includeInMenu);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int $position
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setPosition($position);

    /**
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface[]
     */
    public function getChildren();

    /**
     * @param \Codilar\MegaMenu\Api\Data\CategoryInterface[] $children
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setChildren($children);

    /**
     * @param string $attributeCode
     * @return string
     */
    public function getImageUrl($attributeCode = 'image');

    /**
     * @param string $imageUrl
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setImageUrl($imageUrl);

    /**
     * @return boolean
     */
    public function getIsStatic();

    /**
     * @param boolean $isStatic
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setIsStatic($isStatic);

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMenuImage();

    /**
     * @param string $menuImage
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setMenuImage($menuImage);

    /**
     * @return boolean
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getIsTagalys();

    /**
     * @param boolean $isTagalys
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     */
    public function setIsTagalys($isTagalys);
}
