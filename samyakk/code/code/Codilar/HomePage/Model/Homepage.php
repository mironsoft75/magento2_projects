<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\HomePage\Model;

use Codilar\HomePage\Api\Data\HomepageInterface;
use Magento\Framework\DataObject;

class Homepage extends DataObject implements HomepageInterface
{
    /**
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface[]
     */
    public function getBannerSlider()
    {
        return $this->getData('banner_slider');
    }

    /**
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface[]
     */
    public function getOfferBlocks()
    {
        return $this->getData("offer_blocks");
    }

    /**
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface[]
     */
    public function getShopByCategory()
    {
        return $this->getData("shop_by_category");
    }

    /**
     * @return \Codilar\Cms\Api\Data\BlockInterface[]
     */
    public function getCmsBlock()
    {
        return $this->getData("cms_block");
    }

    /**
     * @param \Codilar\BannerSlider\Api\Data\SliderInterface[] $bannerSlider
     * @return $this|HomepageInterface
     */
    public function setBannerSlider($bannerSlider)
    {
        $this->setData("banner_slider", $bannerSlider);
        return $this;
    }

    /**
     * @param \Codilar\Offers\Api\Data\HomepageBlocksInterface[] $offerBlock
     * @return $this|HomepageInterface
     */
    public function setOfferBlocks($offerBlock)
    {
        $this->setData("offer_blocks", $offerBlock);
        return $this;
    }

    /**
     * @param \Codilar\MegaMenu\Api\Data\CategoryInterface[] $shopByCategory
     * @return $this|HomepageInterface
     */
    public function setShopByCategory($shopByCategory)
    {
        $this->setData("shop_by_category", $shopByCategory);
        return $this;
    }

    /**
     * @param \Codilar\Cms\Api\Data\BlockInterface[] $cmsBlock
     * @return $this|HomepageInterface
     */
    public function setCmsBlock($cmsBlock)
    {
        $this->setData("cms_block", $cmsBlock);
        return $this;
    }

    /**
     * @return \Codilar\Carousel\Api\Data\CarouselInterface[]
     */
    public function getCarousel()
    {
        return $this->getData("carousel");
    }

    /**
     * @param \Codilar\Carousel\Api\Data\CarouselInterface[] $carousel
     * @return \Codilar\HomePage\Api\Data\HomepageInterface
     */
    public function setCarousel($carousel)
    {
        return $this->setData("carousel", $carousel);
    }
}
