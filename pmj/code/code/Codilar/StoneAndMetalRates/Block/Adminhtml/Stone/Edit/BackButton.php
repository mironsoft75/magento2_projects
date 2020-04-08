<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 9/11/18
 * Time: 3:19 PM
 */
namespace Codilar\StoneAndMetalRates\Block\Adminhtml\Stone\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class BackButton
 * @package Codilar\StoneAndMetalRates\Block\Adminhtml\Stone\Edit
 */
class BackButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {

        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}

