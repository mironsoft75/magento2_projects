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
class Cod extends \Magento\Framework\App\Action\Action {

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
    \Magento\Framework\App\Action\Context $context, 
    \Magento\Store\Model\StoreManagerInterface $storeManager, 
    \Softprodigy\Bluedart\Helper\Data $__helper
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
                //echo json_encode($pindata);
                //die;
                $response = $helper->checkCodAvailabel($pindata);
                 
                if(isset($pindata['instring']) and $pindata['instring']==true){
                    if($response['is_error']=='Valid'){
						$html ='';
                        /*if($response['cod_in']=='Yes' and $response['cod_out']=='Yes'){
                            if((float)$pindata['price_limit']<=(float)$response['value_limit']){
                                $html = ucwords(__("Cash On Delivery is <b style='color:green'>Available</b> for - " . $response['place'] . "."));
                            }else{
                                $html = ucwords(__("Product price is gretter than limit -" . $response['value_limit'] . "."));
                            }
                        }else{*/
                            $html =  ucwords(__("Cash On Delivery is <b style='color:red'>Not Available</b> for - " . $response['place'] . "."));
                        //}
                        
                        if($response['prepaid_in']=='Yes' and $response['prepaid_out']=='Yes'){
                            if((float)$pindata['price_limit']<=(float)$response['value_limit']){
                                $html .= "<br/>".ucwords(__("Prepaid Delivery is <b style='color:green'>Available</b> for - " . $response['place'] . "."));
                            } else {
                                $html .= "<br/>".ucwords(__("Product price is gretter than limit -" . $response['value_limit'] . "."));
                            }
                        }else{
                            $html .=  "<br/>".ucwords(__("Prepaid Delivery is <b style='color:red'>Not Available</b> for - " . $response['place'] . "."));
                        }
                        echo $html;
                    }else{
                        echo ucwords(__("Cash On Delivery is <b style='color:red'>Not Available</b> for given pincode."))."<br/>".ucwords(__("Prepaid Delivery is <b style='color:red'>Not Available</b> for given pincode."));
                    }
                    
                    
                }else{
                    echo json_encode($response);
                }
            } else {
                $response['is_error'] = ucwords(__("Invalid request"));
                echo json_encode($response);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }
        die;
    }

}
