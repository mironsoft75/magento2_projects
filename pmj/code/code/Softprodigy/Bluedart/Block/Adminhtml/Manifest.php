<?php
namespace Softprodigy\Bluedart\Block\Adminhtml;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bluedart
 *
 * @author mannu
 */
class Manifest extends \Magento\Backend\Block\Template{
    protected  $_helper;
    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
            \Magento\Backend\Block\Widget\Context $context, 
            \Softprodigy\Bluedart\Helper\Data $_helper ,
            array $data = []
            )
    {
        parent::__construct($context, $data);
        $this->_helper = $_helper;
    }
    
    public function getStoreConfig($path){
        return $this->_helper->getStoreConfig($path);
    }
}
