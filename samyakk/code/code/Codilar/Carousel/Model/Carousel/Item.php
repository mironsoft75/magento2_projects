<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model\Carousel;

use Codilar\Carousel\Api\Data\CarouselItemsInterface;
use Magento\Framework\Model\AbstractModel;
use Codilar\Carousel\Model\ResourceModel\Carousel\Item as ResourceModel;

class Item extends AbstractModel implements CarouselItemsInterface
{

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }


    /**
     * @return string
     */
    public function getContent()
    {
        return $this->getData("content");
    }

    /**
     * @param string $content
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setContent($content)
    {
        return $this->setData("content", $content);
    }

    /**
     * @return \Codilar\Carousel\Api\Data\CarouselItemLinkInterface|string
     */
    public function getLink()
    {
        return $this->getData("link");
    }

    /**
     * @param \Codilar\Carousel\Api\Data\CarouselItemLinkInterface $link
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setLink($link)
    {
        return $this->setData("link", $link);
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
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData("created_at", $createdAt);
    }

    /**
     * @return int
     */
    public function getCarouselId()
    {
        return $this->getData("carousel_id");
    }

    /**
     * @param int $carouselId
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setCarouselId($carouselId)
    {
        return $this->setData("carousel_id", $carouselId);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getData("label");
    }

    /**
     * @param string $label
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setLabel($label)
    {
        return $this->setData("label", $label);
    }
}