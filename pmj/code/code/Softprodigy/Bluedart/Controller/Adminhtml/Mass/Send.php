<?php

namespace Softprodigy\Bluedart\Controller\Adminhtml\Mass;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Softprodigy\Bluedart\Model\Awbsend;
use Magento\Backend\App\Action\Context;
/**
 * Description of Sendshipment
 *
 * @author mannu
 */
class Send extends \Softprodigy\Bluedart\Controller\Adminhtml\AbstractAction {

    private $awbsend;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
            Context $context,
            Awbsend $awbsend
    ) {
        parent::__construct($context);
        $this->awbsend = $awbsend;
    } 

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Softprodigy_Bluedart::Bluedart');
    }

    

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute() {
        $ordersList = $this->getRequest()->getParam('order_ids');
        $params = $this->getRequest()->getParams();
        $orders = explode(",", $ordersList);
        // Create a array
        $stack = array();
        
        foreach($orders as $_order){
            $x = [];
            $x = $params;
            $x['order_id'] = $_order;
            $stack[] = "For Order Id #".$this->awbsend->authHandler($this->_authorization,$this->_objectManager)->resetParam()->setParam($x)->process();
             
        }
        
        $reload = false;
        
        echo json_encode(['error_flex'=>  implode("<br/>", $stack),'reload'=>$reload]); die;
        
        die;
    }

}
