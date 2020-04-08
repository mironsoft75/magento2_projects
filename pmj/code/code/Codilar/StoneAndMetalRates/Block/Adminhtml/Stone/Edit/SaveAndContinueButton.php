<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/11/18
 * Time: 3:22 PM
 */
namespace Codilar\StoneAndMetalRates\Block\Adminhtml\Stone\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveAndContinueButton
 * @package Codilar\StoneAndMetalRates\Block\Adminhtml\Stone\Edit
 */
class SaveAndContinueButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {

        return [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order' => 80,
        ];
    }
}

