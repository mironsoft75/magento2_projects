<?php

namespace Softprodigy\Bluedart\Controller\Check;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cod
 *
 * @author mannu
 */
class Deliverdate extends \Magento\Framework\App\Action\Action {

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     *
     * @var Softprodigy\Bluedart\Helper\Data 
     */
    protected $__helper;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param CustomerCart $cart
     * @codeCoverageIgnore
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Softprodigy\Bluedart\Helper\Data $__helper
    ) {
        $this->_storeManager = $storeManager;
        $this->__helper = $__helper;
        parent::__construct($context);
    }

    public function execute() {

        try {
            $helper = $this->__helper;
            if ($helper->validate()) {
                $pindata = $this->getRequest()->getParams();
                
                $response = $helper->checkEstimatedDateTime($pindata);

                if (isset($pindata['instring']) and $pindata['instring'] == true) {
                    if ($response['is_error'] == 'Valid') {
                        if (!empty($response['expected_delivery'])) {
                            $date = date('l M d, Y', strtotime($response['expected_delivery']));                            
//echo ucwords(__("Estimated Deliver By: %1", $date));
$NewDate=Date('d M Y', strtotime("+6 days"));
echo "Estimated Deliver By: ".$NewDate;
                        } else {
                            echo ucwords(__("Sorry! Unable to get estimated date."));
                        }
                    } else {
                        echo ucwords(__("Sorry! Unable to get estimated date."));
                    }
                } else {
                    if (!empty($response['expected_delivery'])) {
                        $date = date('l M d, Y', strtotime($response['expected_delivery']));
$NewDate=Date('d M Y', strtotime("+6 days"));
$response['expected_delivery'] = ucwords(__("Estimated Deliver By: %1", $NewDate));
//$response['expected_delivery'] = ucwords(__("Estimated Deliver By: %1", $date));
                    }
                    echo json_encode($response);
                }
            } else {
                $response['is_error'] = ucwords(__("Invalid request"));
                echo json_encode($response);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        die;
    }

}
