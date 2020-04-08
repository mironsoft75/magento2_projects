<?php
/**
 * SocialShare
 *
 * @package     Ulmod_SocialShare
 * @author      Ulmod <support@ulmod.com>
 * @copyright   Copyright (c) 2016 Ulmod (http://www.ulmod.com/)
 * @license     http://www.ulmod.com/license-agreement.html
 */
 
namespace Ulmod\SocialShare\Model\System\Config\Source;

class Style implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'style1',
                'label' => __('Style 1 (16×16 buttons)')
            ],
            [
                'value' => 'style2',
                'label' => __('Style 2 (20×20 buttons)')
            ],
            [
                'value' => 'style3',
                'label' => __('Style 3 (32×32 buttons)')
            ],
            [
                'value' => 'style4',
                'label' => __('Style 4 (like, pin it, tweet, etc..)')
            ],
        ];
    }
}
