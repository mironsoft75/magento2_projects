<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model\Source;

class Expand implements \Magento\Framework\Option\ArrayInterface
{
    const AUTO_LABEL = 0;
    const YES_LABEL = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::AUTO_LABEL,
                'label' => __('Auto (based on custom theme)')
            ],
            [
                'value' => self::YES_LABEL,
                'label' => __('Yes')
            ]
        ];
    }
}
