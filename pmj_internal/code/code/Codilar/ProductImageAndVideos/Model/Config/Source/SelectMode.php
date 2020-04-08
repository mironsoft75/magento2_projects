<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 2/7/19
 * Time: 6:57 PM
 */

namespace Codilar\ProductImageAndVideos\Model\Config\Source;

class SelectMode implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' =>'stock_code_ca', 'label' => __('Stock Code using Custom Attribute')]
//            ['value' => 'stock_code', 'label' => __('Stock Code')],
//            ['value' =>'variant_type', 'label' => __('Variant Type')]
        ];
    }
}
