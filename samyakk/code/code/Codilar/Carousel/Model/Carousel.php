<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model;


use Codilar\Carousel\Api\Data\CarouselInterface;
use Magento\Framework\Model\AbstractModel;
use Codilar\Carousel\Model\ResourceModel\Carousel as ResourceModel;

class Carousel extends AbstractModel implements CarouselInterface
{

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getData("title");
    }

    /**
     * @param string $title
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setTitle($title)
    {
        return $this->setData("title", $title);
    }

    /**
     * @return float|int
     */
    public function getSortOrder()
    {
        return $this->getData("sort_order");
    }

    /**
     * @param float|int $sortOrder
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData("sort_order", $sortOrder);
    }

    /**
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface[]
     */
    public function getItems()
    {
        return $this->getData("items");
    }

    /**
     * @param \Codilar\Carousel\Api\Data\CarouselItemsInterface[] $items
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setItems($items)
    {
        return $this->setData("items", $items);
    }

    /**
     * @return int
     */
    public function getIsActive()
    {
        return $this->getData("is_active");
    }

    /**
     * @param int $isActive
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData("is_active", $isActive);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData("created_at");
    }

    /**
     * @param string $createdAt
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData("created_at", $createdAt);
    }

    /**
     * @return string
     */
    public function getStoreViews()
    {
        return $this->getData("store_views");
    }

    /**
     * @param string $storeViews
     * @return \Codilar\Carousel\Api\Data\CarouselInterface
     */
    public function setStoreViews($storeViews)
    {
        return $this->setData("store_views", $storeViews);
    }

    /**
     * @return string
     */
    public function getDesignIdentifier()
    {
        return $this->getData('design_identifier');
    }

    /**
     * @param string $designIdentifier
     * @return $this
     */
    public function setDesignIdentifier($designIdentifier)
    {
        return $this->setData('design_identifier', $designIdentifier);
    }
}