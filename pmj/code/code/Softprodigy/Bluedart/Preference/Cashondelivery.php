<?php
namespace Softprodigy\Bluedart\Preference;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Softprodigy\Bluedart\Helper\Data as blueHeler;
/**
 * Description of Cashondelivery
 *
 * @author mannu
 */
class Cashondelivery extends \Magento\OfflinePayments\Model\Cashondelivery{
    
    /**
     * Check whether payment method can be used
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        if (!$this->isActive($quote ? $quote->getStoreId() : null)) {
            return false;
        }

        $checkResult = new DataObject();
        $checkResult->setData('is_available', true);

        // for future use in observers
        $this->_eventManager->dispatch(
            'payment_method_is_active',
            [
                'result' => $checkResult,
                'method_instance' => $this,
                'quote' => $quote
            ]
        );
        
        if($checkResult->getData('is_available')){
             
            $shippingAddress = $quote->getShippingAddress();
            $zip = $shippingAddress->getPostcode();
            $pindata['pin'] = $zip;
            $response = blueHeler::checkCodAvailabelStat($pindata);
            if($response['is_error']=='Valid'){
                if($response['cod_in']=='Yes' and $response['cod_out']=='Yes'){
                    if((float)$pindata['price_limit']<=(float)$response['value_limit']){
                        return true;
                    }
                }
            }
            return false; 
        }
        
        return $checkResult->getData('is_available');
    }
}
