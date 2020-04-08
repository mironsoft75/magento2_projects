<?php

namespace Softprodigy\Bluedart\Helper;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Data
 *
 * @author mannu
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    protected $scopeConfig;
    protected $_resouceModel;
    protected $filesystem;
    protected $urlInterface;
    protected $_storeManager;
    protected $awblist;
    protected $manifest;

    /**
     * 
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Softprodigy\Dailydeal\Model\Deal $dealFactory
     * @param \Softprodigy\Dailydeal\Model\Deal\Product $dealProductFactory
     * @param \Softprodigy\Dailydeal\Model\Deal\Sales $dealSalesFactory
     * @param \Softprodigy\Dailydeal\Model\Deal\Store $dealStoreFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\App\ResourceConnection $_resouceModel
     */
    public function __construct(
    \Magento\Framework\App\Helper\Context $context, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\App\ResourceConnection $_resouceModel, \Magento\Framework\UrlInterface $urlInterface, \Magento\Framework\Filesystem $filesystem, \Magento\Store\Model\StoreManagerInterface $_storeManager, \Softprodigy\Bluedart\Model\Awblist $awblist, \Softprodigy\Bluedart\Model\Manifest $manifest
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->_resourceModel = $_resouceModel;
        $this->urlInterface = $urlInterface;
        $this->filesystem = $filesystem;
        $this->_storeManager = $_storeManager;
        $this->awblist = $awblist;
        $this->manifest = $manifest;
    }

    public function getStoreConfig($path) {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getIsEnabled() {
        return (int) $this->getStoreConfig('Softprodigy_Bluedart/general/enabled');
    }

    public function urlInterFace() {
        return $this->urlInterface;
    }

    public function filesystem() {
        return $this->filesystem;
    }

    public function getDirPath($dir) {
        return $this->filesystem->getDirectoryRead($dir)->getAbsolutePath();
    }

    public function getMediaUrl() {
        return $this->_storeManager->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function validate() {
        return true;
    }

    public function checkCodAvailabel($pindata) {
        ob_start();

        $ApiUrl = '';
        if ($this->getStoreConfig('Softprodigy_Bluedart/general/sandbox') == 1)
            $ApiUrl = 'http://netconnect.bluedart.com/ver1.7/Demo/ShippingAPI/Finder/ServiceFinderQuery.svc'; //-----For Sandbox-----
        else
            $ApiUrl = 'http://netconnect.bluedart.com/ver1.7/ShippingAPI/Finder/ServiceFinderQuery.svc'; //----For Live----


        $soap = new \Softprodigy\Bluedart\Controller\DebugSoapClient($ApiUrl . '?wsdl', array(
            'trace' => 1,
            'style' => SOAP_DOCUMENT,
            'use' => SOAP_LITERAL,
            'soap_version' => SOAP_1_2
        ));

        $soap->__setLocation($ApiUrl);

        $soap->sendRequest = true;
        $soap->printRequest = false;
        $soap->formatXML = true;


        $actionHeader = new \SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', 'http://tempuri.org/IServiceFinderQuery/GetServicesforPincode', true);
        $soap->__setSoapHeaders($actionHeader);

        $pinvalue = $pindata['pin'];

        $bluedartKey = $this->getStoreConfig('Softprodigy_Bluedart/general/licence_key');
        $loginId = $this->getStoreConfig('Softprodigy_Bluedart/general/login_id');

        $params = array('pinCode' => $pinvalue,
            'profile' =>
            array(
                'Api_type' => 'S',
                'Area' => '',
                'Customercode' => '',
                'IsAdmin' => '',
                'LicenceKey' => $bluedartKey,
                'LoginID' => $loginId,
                'Password' => '',
                'Version' => '1.3')
        );

        $result = $soap->__soapCall('GetServicesforPincode', array($params));
        $response['is_error'] = $result->GetServicesforPincodeResult->ErrorMessage;
        $response['place'] = $result->GetServicesforPincodeResult->PincodeDescription;
        
        $product_code = $this->getStoreConfig('Softprodigy_Bluedart/general/product_code');
        $product_code = !empty($product_code) ? $product_code : 'A';
        
        if($product_code=='A'){
			$response['cod_in'] = $result->GetServicesforPincodeResult->eTailCODAirInbound;
			$response['cod_out'] = $result->GetServicesforPincodeResult->eTailCODAirOutbound;
		} else {
			$response['cod_in'] = $result->GetServicesforPincodeResult->eTailCODGroundInbound;
			$response['cod_out'] = $result->GetServicesforPincodeResult->eTailCODGroundOutbound;
		}
		
		if($product_code=='A'){
			$response['prepaid_in'] = $result->GetServicesforPincodeResult->eTailPrePaidAirInbound;
			$response['prepaid_out'] = $result->GetServicesforPincodeResult->eTailPrePaidAirOutound;
		} else {
			$response['prepaid_in'] = $result->GetServicesforPincodeResult->eTailPrePaidGroundInbound;
			$response['prepaid_out'] = $result->GetServicesforPincodeResult->eTailPrePaidGroundOutbound;
		}
        
        if($product_code=='A'){
			$response['value_limit'] = $result->GetServicesforPincodeResult->AirValueLimit;
	    } else {
			$response['value_limit'] = $result->GetServicesforPincodeResult->GroundValueLimit;
		}
		
        $response['mode'] = $this->getStoreConfig('Softprodigy_Bluedart/general/sandbox');
        ob_end_flush();
        return $response;
    }

    public function getAwbModel() {
        return $this->awblist;
    }

    public function getManifestModel() {
        return $this->manifest;
    }

    public function hasOrderAwbById($order_id) {
        $orderAwb = $this->getAwbModel()->load($order_id, 'order_id');
        return $orderAwb->getId();
    }
	
    public function checkEstimatedDateTime($pindata) {
        ob_start();

        $ApiUrl = '';
        if ($this->getStoreConfig('Softprodigy_Bluedart/general/sandbox') == 1)
            $ApiUrl = 'http://netconnect.bluedart.com/ver1.7/Demo/ShippingAPI/Finder/ServiceFinderQuery.svc'; //-----For Sandbox-----
        else
            $ApiUrl = 'http://netconnect.bluedart.com/ver1.7/ShippingAPI/Finder/ServiceFinderQuery.svc'; //----For Live----


        $opts = array(
            'http' => array(
                'user_agent' => 'PHPSoapClient'
            )
        );
        $scontext = stream_context_create($opts);

        $soap = new \Softprodigy\Bluedart\Controller\DebugSoapClient($ApiUrl . '?wsdl', array(
            'trace' => 1,
            'style' => SOAP_DOCUMENT,
            'use' => SOAP_LITERAL,
            'soap_version' => SOAP_1_2,
            'stream_context' => $scontext,
            'cache_wsdl' => WSDL_CACHE_NONE
        ));

        $soap->__setLocation($ApiUrl);

        $soap->sendRequest = true;
        $soap->printRequest = false;
        $soap->formatXML = true;


        $actionHeader = new \SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', 'http://tempuri.org/IServiceFinderQuery/GetDomesticTransitTimeForPinCodeandProduct', true);
        $soap->__setSoapHeaders($actionHeader);

        $pinvalue = $pindata['pin'];

        $bluedartKey = $this->getStoreConfig('Softprodigy_Bluedart/general/licence_key');
        $loginId = $this->getStoreConfig('Softprodigy_Bluedart/general/login_id');
        $origin_pincode = $this->getStoreConfig('Softprodigy_Bluedart/general/pin_code');
        $pickuptime = $this->getStoreConfig('Softprodigy_Bluedart/general/pickup_time');
        $product_code = $this->getStoreConfig('Softprodigy_Bluedart/general/product_code');
        $product_code = !empty($product_code) ? $product_code : 'A';

        $todaypickuptime = strtotime(date('Y-m-d') . " $pickuptime");
        $currentdate = time();
        $pickupdate = '';
        if ($currentdate > $todaypickuptime) {
            $pickupdate = date('Y-m-d', strtotime('+1 day'));
        } else {
            $pickupdate = date('Y-m-d');
        }
        $pickuptime = date('H:i', $todaypickuptime);

        $params = array(
            'pPinCodeFrom' => $origin_pincode,
            'pPinCodeTo' => $pinvalue,
            'pProductCode' => $product_code,
            'pPudate' => $pickupdate,
            'pPickupTime' => $pickuptime,
            'profile' =>
            array(
                'Api_type' => 'S',
                'Area' => '',
                'Customercode' => '',
                'IsAdmin' => '',
                'LicenceKey' => $bluedartKey,
                'LoginID' => $loginId,
                'Password' => '',
                'Version' => '1.3')
        );

        $result = $soap->__soapCall('GetDomesticTransitTimeForPinCodeandProduct', array($params));

        $coreResult = $result->GetDomesticTransitTimeForPinCodeandProductResult;
        $response['is_error'] = $coreResult->ErrorMessage;
        $response['expected_delivery'] = isset($coreResult->ExpectedDateDelivery) ? $coreResult->ExpectedDateDelivery : '';

        $response['mode'] = $this->getStoreConfig('Softprodigy_Bluedart/general/sandbox');
        ob_end_flush();
        return $response;
    }

}
