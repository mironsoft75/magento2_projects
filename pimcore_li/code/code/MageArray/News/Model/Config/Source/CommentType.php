<?php

namespace MageArray\News\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class CommentType
 * @package MageArray\News\Model\Config\Source
 */
class CommentType implements ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('Disabled')],
            ['value' => 'default', 'label' => __('Default ')],
            ['value' => 'facebook', 'label' => __('Facebook')],
            ['value' => 'google', 'label' => __('Google')],
            ['value' => 'disqus', 'label' => __('Disqus')],
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this->toOptionArray() as $item) {
            $array[$item['value']] = $item['label'];
        }
        return $array;
    }
}
