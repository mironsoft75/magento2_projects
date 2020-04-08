<?php

namespace MageArray\News\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Sorting
 * @package MageArray\News\Model\Config\Source
 */
class Sorting implements ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'desc',
                'label' => __('Newest First')
            ],
            [
                'value' => 'asc',
                'label' => __('Oldest First')
            ]
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [0 => __('Oldest'), 1 => __('Newest')];
    }
}
