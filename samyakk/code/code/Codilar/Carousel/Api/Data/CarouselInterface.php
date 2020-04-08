<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */


namespace Codilar\Carousel\Api\Data;


interface CarouselInterface
{

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setTitle($title);

    /**
     * @return float|int
     */
    public function getSortOrder();

    /**
     * @param float|int $sortOrder
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface[]
     */
    public function getItems();

    /**
     * @param \Codilar\Carousel\Api\Data\CarouselItemsInterface[] $items
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setItems($items);

    /**
     * @return int
     */
    public function getIsActive();

    /**
     * @param int $isActive
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setIsActive($isActive);

    /**
     * @return string
     */
    public function getStoreViews();

    /**
     * @param string $storeViews
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setStoreViews($storeViews);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string
     */
    public function getDesignIdentifier();

    /**
     * @param string $designIdentifier
     * @return $this
     */
    public function setDesignIdentifier($designIdentifier);
}