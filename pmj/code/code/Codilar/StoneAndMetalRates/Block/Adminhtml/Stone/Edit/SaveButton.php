<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/11/18
 * Time: 3:23 PM
 */
namespace Codilar\StoneAndMetalRates\Block\Adminhtml\Stone\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveButton
 * @package Codilar\StoneAndMetalRates\Block\Adminhtml\Stone\Edit
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {

        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}

