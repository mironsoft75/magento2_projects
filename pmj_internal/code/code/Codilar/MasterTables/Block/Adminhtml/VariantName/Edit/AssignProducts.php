<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28/11/19
 * Time: 11:02 AM
 */

namespace Codilar\MasterTables\Block\Adminhtml\VariantName\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class AssignProducts
 *
 * @package Codilar\MasterTables\Block\Adminhtml\VariantName\Edit
 */
class AssignProducts extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get Button Data
     *
     * @return array
     */
    public function getButtonData()
    {
        if (!$this->getId()) {
            return [];
        }
        return [
            'label' => __('Assign Products'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm( \'' . __(
                    'Are you sure you want to  Assign Products to Category?'
                ) . '\', \'' . $this->assignProductsUrl(). '\')',
            'sort_order' => 20,
        ];
    }
}