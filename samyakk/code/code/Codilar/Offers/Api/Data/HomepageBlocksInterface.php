<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Api\Data;

interface HomepageBlocksInterface
{
    CONST DISPLAY_TYPE_CAROUSEL = 1;
    CONST DISPLAY_TYPE_CAROUSEL_LABEL = "Carousel";
    CONST DISPLAY_TYPE_GRID = 2;
    CONST DISPLAY_TYPE_GRID_LABEL = "Grid";
    /**
     * @return int
     */
    public function getBlockId();

    /**
     * @param int $blockId
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setBlockId($blockId);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setTitle($title);

    /**
     * @return boolean
     */
    public function getShowTitle();

    /**
     * @param boolean $showTitle
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setShowTitle($showTitle);

    /**
     * @return int
     */
    public function getDisplayType();

    /**
     * @param int $displayType
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setDisplayType($displayType);

    /**
     * @return string
     */
    public function getStartDate();

    /**
     * @param string $startDate
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setStartDate($startDate);

    /**
     * @return string
     */
    public function getEndDate();

    /**
     * @param string $endDate
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setEndDate($endDate);

    /**
     * @return int
     */
    public function getSortOrder();

    /**
     * @param int $sortOrder
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * @return boolean
     */
    public function getIsActive();

    /**
     * @param boolean $isActive
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setIsActive($isActive);

    /**
     * @return \Codilar\Catalog\Api\Data\ProductInterface[]
     */
    public function getItems();

    /**
     * @param \Codilar\Catalog\Api\Data\ProductInterface[] $items
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setItems($items);

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
