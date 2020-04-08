<?php

namespace Codilar\Rapnet\Block\Adminhtml\UrlRewrite\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 * @package Codilar\Rapnet\Block\Adminhtml\UrlRewrite\Edit
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        if (!$this->getId()) {
            return [];
        }
        return [
            'label' => __('Delete UrlRewrite'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm( \'' . __(
                'Are you sure you want to delete this UrlRewrite?'
            ) . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
    }
}
