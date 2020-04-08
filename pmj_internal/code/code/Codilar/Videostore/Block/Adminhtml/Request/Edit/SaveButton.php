<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 24/11/18
 * Time: 4:20 PM
 */

namespace Codilar\Videostore\Block\Adminhtml\Request\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveButton
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Update'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
//        return [
//            'label' => __('Update'),
//            'class' => 'save primary',
//            'data_attribute' => [
//                'mage-init' => [
//                    'buttonAdapter' => [
//                        'actions' => [
//                            [
//                                'targetName' => 'product_form.product_form',
//                                'actionName' => 'save',
//                                'params' => [
//                                    false
//                                ]
//                            ]
//                        ]
//                    ]
//                ]
//            ],
//        ];
    }
}