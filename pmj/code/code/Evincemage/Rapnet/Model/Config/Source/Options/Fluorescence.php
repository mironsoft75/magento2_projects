<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Evincemage\Rapnet\Model\Config\Source\Options;


class Fluorescence extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        return [
            [
                'label' => 'None',
                'value' => 'None'
            ],
            [
                'label' => 'Faint',
                'value' => 'Faint'
            ],
            [
                'label' => 'Medium',
                'value' => 'Medium'
            ],
            [
                'label' => 'Strong',
                'value' => 'Strong'
            ],
            [
                'label' => 'Very Strong',
                'value' => 'Very Strong'
            ],
        ];
    }
}