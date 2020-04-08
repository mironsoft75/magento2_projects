<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\HomePage\Api\Data;

interface HomepageInterface
{
    /**
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface[]
     */
    public function getBannerSlider();

    /**
     * @param \Codilar\BannerSlider\Api\Data\SliderInterface[] $bannerSlider
     * @return \Codilar\HomePage\Api\Data\HomepageInterface
     */
    public function setBannerSlider($bannerSlider);

    /**
     * @return \Codilar\Carousel\Api\Data\CarouselInterface[]
     */
    public function getCarousel();

    /**
     * @param \Codilar\Carousel\Api\Data\CarouselInterface[] $carousel
     * @return \Codilar\HomePage\Api\Data\HomepageInterface
     */
    public function setCarousel($carousel);

    /**
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface[]
     */
    public function getOfferBlocks();

    /**
     * @param \Codilar\Offers\Api\Data\HomepageBlocksInterface[] $offerBlocks
     * @return \Codilar\HomePage\Api\Data\HomepageInterface
     */
    public function setOfferBlocks($offerBlocks);

    /**
     * @return \Codilar\Category\Api\Data\ShopByCategoryDataInterface
     */
    // public function getShopByCategory();

    /**
     * @param \Codilar\Category\Api\Data\ShopByCategoryDataInterface $shopByCategory
     * @return \Codilar\HomePage\Api\Data\HomepageInterface
     */
    public function setShopByCategory($shopByCategory);

    /**
     * @return \Codilar\Cms\Api\Data\BlockInterface[]
     */
    public function getCmsBlock();

    /**
     * @param \Codilar\Cms\Api\Data\BlockInterface[] $cmsBlock
     * @return \Codilar\HomePage\Api\Data\HomepageInterface
     */
    public function setCmsBlock($cmsBlock);

}
