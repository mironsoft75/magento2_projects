<?php

namespace Softprodigy\Bluedart\Block\Adminhtml\Manifest\History\Renderer;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Link
 *
 * @author mannu
 */
class Link extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer {

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    protected $__helper;


    public function __construct(
            \Magento\Backend\Block\Context $context, 
            \Magento\Framework\Registry $registry, 
            \Softprodigy\Bluedart\Helper\Data $__helper,
            array $data = []) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $registry;
        $this->__helper = $__helper;
    }

    /**
     * Render action
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row) {
        
        return '<a target="_blank" href="'.$this->__helper->getMediaUrl() . "bluredart_pdf/" . $row->getData('file_name').'">'.$row->getData('file_name').'</a>';
    }

}
