<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Evincemage\Rapnet\Model\Config\Source\Options;


class Certificates extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        return [
            [
                'label' =>'GIA',
                'value' =>'GIA'
            ],
            [
                'label' =>'EGL USA',
                'value' =>'EGL USA'
            ],
            [
                'label' =>'EGL Israel',
                'value' =>'EGL Israel'
            ],
            [
                'label' =>'IGI',
                'value' =>'IGI'
            ],
            [
                'label' =>'AGS',
                'value' =>'AGS'
            ],
            [
                'label' =>'HRD',
                'value' =>'HRD'
            ]
        ];
    }
}