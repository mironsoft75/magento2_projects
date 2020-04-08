<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */


namespace Codilar\Carousel\Api\Data;


interface CarouselItemsInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getCarouselId();

    /**
     * @param int $carouselId
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setCarouselId($carouselId);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setContent($content);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setLabel($label);

    /**
     * @return \Codilar\Carousel\Api\Data\CarouselItemLinkInterface
     */
    public function getLink();

    /**
     * @param \Codilar\Carousel\Api\Data\CarouselItemLinkInterface|string $link
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setLink($link);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function setCreatedAt($createdAt);
}