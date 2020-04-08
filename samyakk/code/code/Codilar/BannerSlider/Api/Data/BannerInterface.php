<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Api\Data;


interface BannerInterface
{

    /**
     * @return int
     */
    public function getBannerId();

    /**
     * @param int $bannerId
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setBannerId($bannerId);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setName($name);

    /**
     * @param bool $removeBase
     * @return string
     */
    public function getClickUrl($removeBase = true);

    /**
     * @param string $clickUrl
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setClickUrl($clickUrl);

    /**
     * @return string
     */
    public function getImage();

    /**
     * @param string $image
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setImage($image);

    /**
     * @return string
     */
    public function getImageText();

    /**
     * @param string $imageText
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setImageText($imageText);

    /**
     * @param string $altText
     * @return $this
     */
    public function setAltText($altText);

    /**
     * @return string
     */
    public function getAltText();

    /**
     * @return string
     */
    public function getMobileImage();

    /**
     * @param string $mobileImage
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setMobileImage($mobileImage);

    /**
     * @return string
     */
    public function getMobileImageText();

    /**
     * @param string $mobileImageText
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setMobileImageText($mobileImageText);

    /**
     * @return string
     */
    public function getTabletImage();

    /**
     * @param string $tabletImage
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setTabletImage($tabletImage);

    /**
     * @return string
     */
    public function getTabletImageText();

    /**
     * @param string $tabletImageText
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setTabletImageText($tabletImageText);

    /**
     * @return string
     */
    public function getStartTime();

    /**
     * @param string $startTime
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setStartTime($startTime);

    /**
     * @return string
     */
    public function getEndTime();

    /**
     * @param string $endTime
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setEndTime($endTime);

    /**
     * @return bool
     */
    public function getStatus();

    /**
     * @param bool $status
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setStatus($status);

    /**
     * @return int
     */
    public function getOrderBanner();

    /**
     * @param int $orderBanner
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setOrderBanner($orderBanner);

}
