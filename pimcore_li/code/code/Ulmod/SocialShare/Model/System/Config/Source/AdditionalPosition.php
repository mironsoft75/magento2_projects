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

use Magento\Framework\Option\ArrayInterface;

class AdditionalPosition implements ArrayInterface
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
                'value' => 'right',
                'label' => __('Right')
            ],
            [
                'value' => 'left',
                'label' => __('Left')
            ],
        ];
    }
}
