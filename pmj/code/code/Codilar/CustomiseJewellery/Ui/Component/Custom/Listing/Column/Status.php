<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/11/18
 * Time: 12:26 PM
 */
namespace Codilar\CustomiseJewellery\Ui\Component\Custom\Listing\Column;

/**
 * Class Status
 * @package Codilar\CustomiseJewellery\Ui\Component\Custom\Listing\Column
 */
class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => '0', 'label' => __('New')], ['value' => '1', 'label' => __('Pending')],['value' => '2', 'label' => __('Processing')],['value' => '3', 'label' => __('Complete')],['value' => '4', 'label' => __('Canceled')]];
    }
}