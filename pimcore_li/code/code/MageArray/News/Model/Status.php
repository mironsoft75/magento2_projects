<?php

namespace MageArray\News\Model;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 * @package MageArray\News\Model
 */
class Status implements ArrayInterface
{
    /**
     *
     */
    const STATUS_ENABLED = 1;
    /**
     *
     */
    const STATUS_DISABLED = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function getOptionArray()
    {
        return [
            self::STATUS_ENABLED => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled')
        ];
    }

    /**
     * @return array
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * @param $optionId
     * @return mixed|null
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }
}
