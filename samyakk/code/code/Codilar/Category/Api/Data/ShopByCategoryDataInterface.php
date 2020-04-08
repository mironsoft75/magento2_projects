<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/4/19
 * Time: 10:51 AM
 */

namespace Codilar\Category\Api\Data;


interface ShopByCategoryDataInterface
{
    /**
     * @return int
     */
    public function getSortOrder();

    /**
     * @param int $sortOrder
     * @return \Codilar\Category\Api\Data\ShopByCategoryDataInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return \Codilar\Category\Api\Data\ShopByCategoryDataInterface
     */
    public function setTitle($title);

    /**
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface[]
     */
    public function getCategories();

    /**
     * @param \Codilar\MegaMenu\Api\Data\CategoryInterface[] $categories
     * @return \Codilar\Category\Api\Data\ShopByCategoryDataInterface
     */
    public function setCategories($categories);
}