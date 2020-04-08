<?php

namespace MageArray\News\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Pagelayout
 * @package MageArray\News\Model\Config\Source
 */
class Pagelayout implements ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '1column',
                'label' => __('1column')
            ],
            [
                'value' => '2columns-left',
                'label' => __('2columns Left Side Bar')
            ],
            [
                'value' => '2columns-right',
                'label' => __('2columns Right Side Bar')
            ],
            [
                'value' => '3columns',
                'label' => __('3columns')
            ]
        ];
    }
}
