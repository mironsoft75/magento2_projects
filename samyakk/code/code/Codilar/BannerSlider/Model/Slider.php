<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Model;

use Codilar\BannerSlider\Api\Data\BannerInterface;
use Codilar\BannerSlider\Api\Data\SliderInterface;
use Codilar\BannerSlider\Api\SliderRepositoryInterface;
use Magento\Framework\Api\SortOrder;
use Magestore\Bannerslider\Model\ResourceModel\Banner\Collection;
use Magestore\Bannerslider\Model\Slider as Subject;

class Slider extends Subject implements SliderInterface
{
    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * @var \Magestore\Bannerslider\Model\ResourceModel\Banner\CollectionFactory
     */
    private $bannerCollectionFactory;

    /**
     * Slider constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magestore\Bannerslider\Model\ResourceModel\Banner\CollectionFactory $bannerCollectionFactory
     * @param \Magestore\Bannerslider\Model\ResourceModel\Slider $resource
     * @param \Magestore\Bannerslider\Model\ResourceModel\Slider\Collection $resourceCollection
     * @param SliderRepositoryInterface $sliderRepository
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magestore\Bannerslider\Model\ResourceModel\Banner\CollectionFactory $bannerCollectionFactory,
        \Magestore\Bannerslider\Model\ResourceModel\Slider $resource,
        \Magestore\Bannerslider\Model\ResourceModel\Slider\Collection $resourceCollection,
        SliderRepositoryInterface $sliderRepository
    )
    {
        parent::__construct($context, $registry, $bannerCollectionFactory, $resource, $resourceCollection);
        $this->sliderRepository = $sliderRepository;
        $this->bannerCollectionFactory = $bannerCollectionFactory;
    }

    /**
     * @return int
     */
    public function getSliderId()
    {
        return $this->getId();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getData("title");
    }

    /**
     * @return string
     */
    public function getShowInHomePage()
    {
        return $this->getData("show_in_homepage");
    }

    /**
     * @return string
     */
    public function getShowTitle()
    {
        return $this->getData("show_title");
    }

    /**
     * @return string
     */
    public function getAnimationB()
    {
        return $this->getData("animationB");
    }

    /**
     * @return \Codilar\BannerSlider\Api\Data\BannerInterface[]|null
     */
    public function getBanners()
    {
        $banners = [];
        /** @var Collection $bannerCollection */
        $bannerCollection = $this->bannerCollectionFactory->create()
            ->addFieldToFilter("slider_id", $this->getSliderId())
            ->addFieldToFilter("status", 1)
            ->setOrder("order_banner", SortOrder::SORT_ASC);
        if ($bannerCollection->getSize()) {
            /** @var BannerInterface $banner */
            foreach ($bannerCollection as $banner) {
                $banners[] = $banner;
            }

        }
        return $banners;
    }

    /**
     * @param int $sliderId
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setSliderId($sliderId)
    {
        return $this->setData("slider_id", $sliderId);
    }

    /**
     * @param string $title
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setTitle($title)
    {
        return $this->setData("title", $title);
    }

    /**
     * @param bool $showInHomePage
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setShowInHomePage($showInHomePage)
    {
        return $this->setData("show_in_home_page", $showInHomePage);
    }

    /**
     * @param bool $showTitle
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setShowTitle($showTitle)
    {
        return $this->setData("show_title", $showTitle);
    }

    /**
     * @param string $animationB
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setAnimationB($animationB)
    {
        return $this->setData("animation_b", $animationB);
    }

    /**
     * @param \Codilar\BannerSlider\Api\Data\BannerInterface[] $banners
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setBanners($banners)
    {
        return $this->setData("banners", $banners);
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getData("sort_order");
    }

    /**
     * @param int $sortOrder
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData("sort_order", $sortOrder);
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
