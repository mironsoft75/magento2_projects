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

class Services implements \Magento\Framework\Option\ArrayInterface
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
                'value' => 'display_all_services',
                'label' => __('Display all sharing services')
            ],
            [
                'value' => 'display_individual_services',
                'label' => __('Display individual sharing services')
            ],
        ];
    }
}
