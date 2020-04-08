<?php
namespace Softprodigy\Bluedart\Block\Adminhtml\Mass;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Orders
 *
 * @author mannu
 */
class Orders  extends \Magento\Backend\Block\Widget\Grid\Container {
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Softprodigy_Bluedart';
        $this->_controller = 'adminhtml_mass_orders';
        $this->_headerText = __('select orders and click on generate AWB');
        $this->_addButtonLabel = __('Generate AWB');
        parent::_construct();
        $this->updateButton('add', 'onclick', 'getMassPopup();');
         
    }
    protected function _prepareLayout(){
        parent::_prepareLayout();
    }
}