<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Api;

use Magestore\Bannerslider\Model\ResourceModel\Slider\Collection;

interface SliderRepositoryInterface
{
    /**
     * @param $sliderId
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface
     */
    public function getById($sliderId);

    /**
     * @return Collection
     */
    public function getCollection();

    /**
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface[]
     */
    public function getHomePageSlider();
}
