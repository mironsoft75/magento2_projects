<?php

namespace WeltPixel\OwlCarouselSlider\Helper;

/**
 * Helper Custom Slider
 * @category WeltPixel
 * @package  WeltPixel_OwlCarouselSlider
 * @module   OwlCarouselSlider
 * @author   WeltPixel Developer
 */
class Custom extends \Magento\Framework\App\Helper\AbstractHelper
{

     /**
     * @param \WeltPixel\OwlCarouselSlider\Model\Slider          $sliderModel
     */

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \WeltPixel\OwlCarouselSlider\Model\Slider $sliderModel,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        parent::__construct($context);

        $this->_sliderModel = $sliderModel;
        $this->_date        = $date;
        $this->_scopeConfig = $context->getScopeConfig();
    }

}
