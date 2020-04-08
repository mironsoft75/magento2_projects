<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Api\Data;


interface SliderInterface
{

    /**
     * @return int
     */
    public function getSliderId();

    /**
     * @param int $sliderId
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setSliderId($sliderId);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setTitle($title);

    /**
     * @return int
     */
    public function getSortOrder();

    /**
     * @param int $sortOrder
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * @return bool
     */
    public function getShowInHomePage();

    /**
     * @param bool $showInHomePage
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setShowInHomePage($showInHomePage);

    /**
     * @return bool
     */
    public function getShowTitle();

    /**
     * @param bool $showTitle
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setShowTitle($showTitle);

    /**
     * @return string
     */
    public function getAnimationB();

    /**
     * @param string $animationB
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setAnimationB($animationB);

    /**
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface[]
     */
    public function getBanners();

    /**
     * @param \Codilar\BannerSlider\Api\Data\BannerInterface[] $banners
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setBanners($banners);

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
