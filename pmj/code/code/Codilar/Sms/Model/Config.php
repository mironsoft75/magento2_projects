<?php

namespace Codilar\Sms\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Api\Data\StoreConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config {

    protected $scopeConfig;
    protected $storeManager;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getStore(){
        return $this->storeManager->getStore();
    }

    /**
     * @return bool
     */
    public function isEnabled(){
        return (bool)$this->getValue("enabled");
    }

    /**
     * @return mixed
     */
    public function getAuthId(){
        return $this->getValue("auth_id");
    }

    /**
     * @return mixed
     */
    public function getAuthToken(){
        return $this->getValue("auth_token");
    }

    /**
     * @return mixed
     */
    public function getSourceNumber(){
        return $this->getValue("src");
    }

    /**
     * @return mixed
     */
    public function getStoreNumber() {
        return $this->getValue("phone", "store_information", "general");
    }

    /**
     * @return mixed
     */
    public function getOrderPlaced() {
        return $this->getValue('pending_place','alerts','sms');
    }

    /**
     * @return mixed
     */
    public function getOrderConfirm() {
        return $this->getValue('processing_confirm','alerts','sms');
    }

    /**
     * @return mixed
     */
    public function getOrderDispatched() {
        return $this->getValue('dispatched','alerts','sms');
    }

    /**
     * @return mixed
     */
    public function getOrderOutForDelivery() {
        return $this->getValue('out_for_delivery','alerts','sms');
    }

    /**
     * @return mixed
     */
    public function getOrderDelivered() {
        return $this->getValue('complete_delivered','alerts','sms');
    }

    /**
     * @return mixed
     */
    public function getOrderCanceled() {
        return $this->getValue('canceled','alerts','sms');
    }

    public function getCountryCode() {
        return $this->getValue('country_code');
    }

    /**
     * @param $field
     * @param string $group
     * @param string $section
     * @return mixed
     */
    public function getValue($field, $group = "general", $section = "sms"){
        return $this->scopeConfig->getValue($section."/".$group."/".$field, ScopeInterface::SCOPE_STORE);
    }
    /**
     * @return bool
     */
    public function isMsg91Enabled(){
        return (bool)$this->getMSg91Value("enabled");
    }

    /**
     * @return mixed
     */
    public function getMsg91AuthKey(){
        return $this->getMSg91Value("auth_key");
    }
    /**
     * @return mixed
     */
    public function getMsg91SenderId(){
        return $this->getMSg91Value("sender");
    }
    /**
     * @param $field
     * @param string $group
     * @param string $section
     * @return mixed
     */
    public function getMSg91Value($field, $group = "msg91", $section = "sms"){
        return $this->scopeConfig->getValue($section."/".$group."/".$field, ScopeInterface::SCOPE_STORE);
    }
}