<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Codilar\Rapnet\Model\Config\Source\Options;

class Clarity extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        return [
            [
                'label' =>'FL',
                'value' =>'FL'
            ],
            [
                'label' =>'IF',
                'value' =>'IF'
            ],
            [
                'label' =>'VVS1',
                'value' =>'VVS1'
            ],
            [
                'label' =>'VVS2',
                'value' =>'VVS2'
            ],
            [
                'label' =>'VS1',
                'value' =>'VS1'
            ],
            [
                'label' =>'VS2',
                'value' =>'VS2'
            ],
            [
                'label' =>'SI1',
                'value' =>'SI1'
            ],
            [
                'label' =>'SI2',
                'value' =>'SI2'
            ],
            [
                'label' =>'SI3',
                'value' =>'SI3'
            ],
            [
                'label' =>'I1',
                'value' =>'I1'
            ],
            [
                'label' =>'I2',
                'value' =>'I2'
            ],
            [
                'label' =>'I3',
                'value' =>'I3'
            ]
        ];
    }
}
