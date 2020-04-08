<?php

/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model\Source;

use Codilar\Carousel\Api\Data\CarouselItemLinkInterface;
use Magento\Framework\Data\OptionSourceInterface;

class LinkTypeOptions implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => CarouselItemLinkInterface::LINK_TYPE_PRODUCT,
                'label' => CarouselItemLinkInterface::LINK_TYPE_PRODUCT_LABEL
            ],
            [
                'value' => CarouselItemLinkInterface::LINK_TYPE_CATEGORY,
                'label' => CarouselItemLinkInterface::LINK_TYPE_CATEGORY_LABEL
            ],
            [
                'value' => CarouselItemLinkInterface::LINK_TYPE_CMS,
                'label' => CarouselItemLinkInterface::LINK_TYPE_CMS_Label
            ],
            [
                'value' => CarouselItemLinkInterface::LINK_TYPE_NONE,
                'label' => CarouselItemLinkInterface::LINK_TYPE_NONE_Label
            ]
        ];
        return $options;
    }
}