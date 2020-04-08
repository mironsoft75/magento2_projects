<?php

namespace Softprodigy\Bluedart\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Magento\Framework\DataObject;
use Magento\Sales\Model\Order\Payment;

/**
 * Description of Cashondelivery
 *
 * @author mannu
 */
class Cashondelivery extends \Magento\OfflinePayments\Model\Cashondelivery {

    protected $__blueHelper;

    /**
     * Check whether payment method can be used
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null) {
        if (!$this->isActive($quote ? $quote->getStoreId() : null)) {
            return false;
        }
        $return = true;
        $checkResult = new DataObject();
        $checkResult->setData('is_available', true);

        // for future use in observers
        $this->_eventManager->dispatch(
                'payment_method_is_active', [
            'result' => $checkResult,
            'method_instance' => $this,
            'quote' => $quote
                ]
        );

        $return = $checkResult->getData('is_available');
        if ($return==true) {
            $zip = '';
            $total = 0;
            try {
                $paymentInfo = $this->getInfoInstance();
                if ($paymentInfo instanceof Payment) {
                    $zip = $paymentInfo->getOrder()->getShippingAddress()->getPostcode();
                    $total = $paymentInfo->getOrder()->getGrandTotal();
                } else {
                    $zip = $paymentInfo->getQuote()->getShippingAddress()->getPostcode();
                    $total = $paymentInfo->getQuote()->getGrandTotal();
                }
            } catch (\Exception $ex) {
                if ($quote and $quote->getId()) {
                    $zip = $quote->getShippingAddress()->getPostcode();
                    $total = $quote->getGrandTotal();
                }
            }
            
            try {
                $zip = trim($zip);
                if(!empty($zip) && is_numeric($zip)){
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $this->__blueHelper = $objectManager->get('Softprodigy\Bluedart\Helper\Data');
                    $messageManager = $objectManager->get('Magento\Framework\Message\ManagerInterface');

                    $pindata = [];
                    $pindata['pin'] = $zip;
                    $response = [];
                    $response = $this->__blueHelper->checkCodAvailabel($pindata);
                    //var_dump($response); exit;
                    if (!empty($response) && 'Valid'==$response['is_error']) {
                        if (isset($response['cod_in']) and isset($response['cod_out']) && ('Yes' ==  ($response['cod_in']== $response['cod_out']))) {
                           $return = ((float)$total<=(float)$response['value_limit'])? true: false;
                        }else{
                            $return = false;
                        }
                    }else{
                        $return = false;
                    }
                    if ($return === false) {
                        $messageManager->addError(__('Cash on delivery is not available for given address.'));
                    }
                    unset($response);
                }
            } catch (\Exception $ex) {
                
            }
        }
        
        return $return;
    }

}
