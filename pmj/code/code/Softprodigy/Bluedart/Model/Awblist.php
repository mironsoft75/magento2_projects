<?php
namespace Softprodigy\Bluedart\Model;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Magento\Framework\Model\AbstractModel;
/**
 * Description of Deal
 *
 * @author mannu
 */
class Awblist extends AbstractModel{
    public function _construct() {
        $this->_init('Softprodigy\Bluedart\Model\ResourceModel\Awblist');
    }
    
}
 