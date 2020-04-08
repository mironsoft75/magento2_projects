<?php
namespace WeltPixel\InstagramWidget\Model\Config\Source;

class FeedType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'user', 'label' => __('User')],
            ['value' => 'tagged', 'label' => __('Tagged')],
            ['value' => 'location', 'label' => __('Location')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'user' => __('User'),
            'tagged' => __('Tagged'),
            'location' => __('Location')
        ];
    }
}
