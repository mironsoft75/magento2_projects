<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Evincemage\Rapnet\Model\Config\Source\Options;


class Polish extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        return [
            [
                'label' =>'Excellent',
                'value' =>'Excellent'
            ],
            [
                'label' =>'Very Good',
                'value' =>'Very Good'
            ],
            [
                'label' =>'Good',
                'value' =>'Good'
            ],
            [
                'label' =>'Fair',
                'value' =>'Fair'
            ],
            [
                'label' =>'Poor',
                'value' =>'Poor'
            ]
        ];

    }
}