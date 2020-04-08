<?php

namespace Codilar\Api\Api;

use Codilar\Api\Helper\Cookie;
use Codilar\Customer\Helper\Data as CustomerHelper;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Webapi\Rest\Response;

class AbstractApi{

    const API_VERSION = "2";

    protected $cookieHelper;
    protected $request;
    protected $response;
    protected $customerSession;
    protected $dataObjectFactory;
    protected $customerHelper;

    protected $_headers = [];

    function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        CustomerHelper $customerHelper
    )
    {
        $this->cookieHelper = $cookieHelper;
        $this->request = $request;
        $this->response = $response;
        $this->customerSession = $customerSession;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->customerHelper = $customerHelper;
    }

    public function setHeader($name, $value){
        $this->_headers[$name] = $value;
    }

    protected function sendResponse($response){
        $this->setSessionInfoToHeader();
        $this->setHeaders();
        return $response;
    }

    /**
     * @param array $data
     * @return DataObject
     */
    public function getNewDataObject($data = []){
        return $this->dataObjectFactory->create()->setData($data);
    }

    protected function setSessionInfoToHeader(){
        if(!isset($this->_headers['expired'])) {
            $this->_headers['expired'] = !$this->customerSession->isLoggedIn();
        }
        $this->_headers['session_id'] = $this->cookieHelper->getPhpSessionCookie();
        $this->_headers['session_name'] = $this->cookieHelper->getSessionCookieName();
        $this->_headers['customer_state'] = $this->customerHelper->getCustomerState($this->customerSession->getId());
        $this->_headers['api_version'] = self::API_VERSION;
    }

    protected function setHeaders(){
        $sessionHeader = [];
        foreach($this->_headers as $name => $value){
            $sessionHeader[$name] = $value;
        }
        $this->response->setHeader('session', json_encode($sessionHeader));
    }

}