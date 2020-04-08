<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Codilar\Rapnet\Model\Config\Source\Options;

class Shape extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        return [
            [
                'label' =>'Round',
                'value' =>'Round'
            ],
            [
                'label' =>'Princess',
                'value' =>'Princess'
            ],
            [
                'label' =>'Emerald',
                'value' =>'Emerald'
            ],
            [
                'label' =>'Radiant',
                'value' =>'Radiant'
            ],
            [
                'label' =>'Cushion',
                'value' =>'Cushion'
            ],
            [
                'label' =>'Pear',
                'value' =>'Pear'
            ],
            [
                'label' =>'Marquise',
                'value' =>'Marquise'
            ],
            [
                'label' =>'Oval',
                'value' =>'Oval'
            ],
            [
                'label' =>'Asscher',
                'value' =>'Asscher'
            ],
            [
                'label' =>'Heart',
                'value' =>'Heart'
            ],
            [
                'label' =>'Trillion',
                'value' =>'Trillion'
            ]

        ];
    }
}
