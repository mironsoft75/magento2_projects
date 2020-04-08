<?php
namespace Softprodigy\Bluedart\Model\ResourceModel;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
/**
 * Description of Deal
 *
 * @author mannu
 */
class Awblist extends  AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
         $this->_init('bluedart_awb_list','id');
    }
}
