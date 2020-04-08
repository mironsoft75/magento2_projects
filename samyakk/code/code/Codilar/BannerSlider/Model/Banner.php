<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Model;

use Codilar\BannerSlider\Api\Data\BannerInterface;
use Codilar\BannerSlider\Helper\Banner as BannerHelper;
use Magestore\Bannerslider\Model\Banner as Subject;
use Magestore\Bannerslider\Helper\Data;

class Banner extends Subject implements BannerInterface
{
    /**
     * @var BannerHelper
     */
    private $bannerHelper;
    /**
     * @var Data
     */
    private $helper;

    /**
     * Banner constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magestore\Bannerslider\Model\ResourceModel\Banner $resource
     * @param \Magestore\Bannerslider\Model\ResourceModel\Banner\Collection $resourceCollection
     * @param \Magestore\Bannerslider\Model\BannerFactory $bannerFactory
     * @param \Magestore\Bannerslider\Model\ValueFactory $valueFactory
     * @param \Magestore\Bannerslider\Model\ResourceModel\Slider\CollectionFactory $sliderCollectionFactory
     * @param \Magestore\Bannerslider\Model\ResourceModel\Value\CollectionFactory $valueCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Logger\Monolog $monolog
     * @param BannerHelper $bannerHelper
     * @param Data $helper
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magestore\Bannerslider\Model\ResourceModel\Banner $resource,
        \Magestore\Bannerslider\Model\ResourceModel\Banner\Collection $resourceCollection,
        \Magestore\Bannerslider\Model\BannerFactory $bannerFactory,
        \Magestore\Bannerslider\Model\ValueFactory $valueFactory,
        \Magestore\Bannerslider\Model\ResourceModel\Slider\CollectionFactory $sliderCollectionFactory,
        \Magestore\Bannerslider\Model\ResourceModel\Value\CollectionFactory $valueCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Logger\Monolog $monolog,
        BannerHelper $bannerHelper,
        Data $helper
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $bannerFactory, $valueFactory, $sliderCollectionFactory, $valueCollectionFactory, $storeManager, $monolog);
        $this->bannerHelper = $bannerHelper;
        $this->helper = $helper;
    }

    /**
     * @return int
     */
    public function getBannerId()
    {
        return $this->getId();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData("name");
    }

    /**
     * @param bool $removeBase
     * @return string
     */
    public function getClickUrl($removeBase = true)
    {
        $url = $this->getData("click_url");
        $updatedUrl = $url;
        if ($url) {
            if ($removeBase) {
                $updatedUrl = parse_url($this->getData("click_url"));
                if (array_key_exists("path", $updatedUrl)) {
                    $updatedUrl = $updatedUrl['path'];
                } else {
                    $updatedUrl = $url;
                }
            }
        }
        return $updatedUrl;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->helper->getBaseUrlMedia($this->getData("image"));
    }

    /**
     * @return string
     */
    public function getImageText()
    {
        return $this->getData("image_text");
    }

    /**
     * @param string $altText
     * @return $this
     */
    public function setAltText($altText)
    {
        return $this->setData("image_alt", $altText);
    }

    /**
     * @return string
     */
    public function getAltText()
    {
        return $this->getData("image_alt");
    }

    /**
     * @return string
     */
    public function getMobileImage()
    {
        return $this->helper->getBaseUrlMedia($this->getData("mobile_image"));
    }

    /**
     * @return string
     */
    public function getMobileImageText()
    {
        return $this->getData("mobile_image_text");
    }

    /**
     * @return string
     */
    public function getTabletImage()
    {
        return $this->helper->getBaseUrlMedia($this->getData("tablet_image"));
    }

    /**
     * @return string
     */
    public function getTabletImageText()
    {
        return $this->getData("tablet_image_text");
    }

    /**
     * @return string
     */
    public function getStartTime()
    {
        return $this->getData("start_time");
    }

    /**
     * @return string
     */
    public function getEndTime()
    {
        return $this->getData("end_time");
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->getData("status");
    }

    /**
     * @return int
     */
    public function getOrderBanner()
    {
        return $this->getData("order_banner");
    }

    /**
     * @param int $bannerId
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setBannerId($bannerId)
    {
        return $this->setData("banner_id", $bannerId);
    }

    /**
     * @param string $name
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setName($name)
    {
        return $this->setData("name", $name);
    }

    /**
     * @param string $clickUrl
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setClickUrl($clickUrl)
    {
        return $this->setData("click_url", $clickUrl);
    }

    /**
     * @param string $image
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setImage($image)
    {
        return $this->setData("image", $image);
    }

    /**
     * @param string $imageText
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setImageText($imageText)
    {
        return $this->setData("image_text", $imageText);
    }

    /**
     * @param string $mobileImage
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setMobileImage($mobileImage)
    {
        return $this->setData("mobile_image", $mobileImage);
    }

    /**
     * @param string $mobileImageText
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setMobileImageText($mobileImageText)
    {
        return $this->setData("mobile_image_text", $mobileImageText);
    }

    /**
     * @param string $tabletImage
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setTabletImage($tabletImage)
    {
        return $this->setData("tablet_image", $tabletImage);
    }

    /**
     * @param string $tabletImageText
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setTabletImageText($tabletImageText)
    {
        return $this->setData("tablet_image_text", $tabletImageText);
    }

    /**
     * @param string $startTime
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setStartTime($startTime)
    {
        return $this->setData("start_time", $startTime);
    }

    /**
     * @param string $endTime
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setEndTime($endTime)
    {
        return $this->setData("end_time", $endTime);
    }

    /**
     * @param bool $status
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setStatus($status)
    {
        return $this->setData("status", $status);
    }

    /**
     * @param int $orderBanner
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface
     */
    public function setOrderBanner($orderBanner)
    {
        return $this->setData("order_banner", $orderBanner);
    }
}
