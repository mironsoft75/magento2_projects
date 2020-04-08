<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Model\Resolver\DataProvider;

use Codilar\BannerSlider\Api\Data\SliderInterface;
use Codilar\BannerSlider\Api\SliderRepositoryInterface;

class Banners
{
    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * Banners constructor.
     * @param SliderRepositoryInterface $sliderRepository
     */
    public function __construct(
        SliderRepositoryInterface $sliderRepository
    )
    {
        $this->sliderRepository = $sliderRepository;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = [];
        $sliderCollection = $this->sliderRepository->getCollection();
        if ($sliderCollection->getSize()) {
            /** @var SliderInterface $slider */
            $slider = $sliderCollection->getFirstItem();
            $sliderBanners = $this->sliderRepository->getSliderBanners($slider->getSliderId());
            $data = [
                "slider_id" => $slider->getSliderId(),
                "slider_title" => $slider->getTitle(),
                "slider_animation" => $slider->getAnimation(),
                "show_title" => $slider->getShowTitle(),
                "banners" => $sliderBanners
            ];
        }
        return $data;
    }
}