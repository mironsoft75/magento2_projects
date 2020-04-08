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
class Mass extends \Magento\Backend\Block\Template{
    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
            \Magento\Backend\Block\Widget\Context $context, 
            array $data = []
            )
    {
        parent::__construct($context, $data);
    }
}
