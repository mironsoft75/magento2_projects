<?php

namespace Codilar\MasterTables\Block\Adminhtml\MetalBom\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 * @package Codilar\MasterTables\Block\Adminhtml\MetalBom\Edit
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
            'label' => __('Delete MetalBom'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm( \'' . __(
                'Are you sure you want to delete this MetalBom?'
            ) . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
    }
}
