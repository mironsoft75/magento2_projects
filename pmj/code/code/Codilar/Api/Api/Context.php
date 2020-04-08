<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/1/18
 * Time: 1:31 PM
 */

namespace Codilar\Api\Api;

use Codilar\Api\Helper\Cookie;
use Codilar\Customer\Helper\Data as CustomerHelper;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Webapi\Rest\Response;
use Magento\Store\Model\StoreManagerInterface;

class Context
{
    /**
     * @var Cookie
     */
    private $cookieHelper;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var Response
     */
    private $response;
    /**
     * @var Session
     */
    private $customerSession;
    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;
    /**
     * @var CustomerHelper
     */
    private $customerHelper;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Zend_Controller_Request_Http
     */
    private $zend_Controller_Request_Http;

    /**
     * Context constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param CustomerHelper $customerHelper
     * @param StoreManagerInterface $storeManager
     * @param \Zend_Controller_Request_Http $zend_Controller_Request_Http
     */
    function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        CustomerHelper $customerHelper,
        StoreManagerInterface $storeManager,
        \Zend_Controller_Request_Http $zend_Controller_Request_Http
    )
    {

        $this->cookieHelper = $cookieHelper;
        $this->request = $request;
        $this->response = $response;
        $this->customerSession = $customerSession;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->customerHelper = $customerHelper;
        $this->storeManager = $storeManager;
        $this->zend_Controller_Request_Http = $zend_Controller_Request_Http;
    }

    /**
     * @return Cookie
     */
    public function getCookieHelper(): Cookie
    {
        return $this->cookieHelper;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return Session
     */
    public function getCustomerSession(): Session
    {
        return $this->customerSession;
    }

    /**
     * @return DataObjectFactory
     */
    public function getDataObjectFactory(): DataObjectFactory
    {
        return $this->dataObjectFactory;
    }

    /**
     * @return CustomerHelper
     */
    public function getCustomerHelper(): CustomerHelper
    {
        return $this->customerHelper;
    }

    /**
     * @return StoreManagerInterface
     */
    public function getStoreManager(): StoreManagerInterface
    {
        return $this->storeManager;
    }

    /**
     * @return \Zend_Controller_Request_Http
     */
    public function getZendControllerRequestHttp(): \Zend_Controller_Request_Http
    {
        return $this->zend_Controller_Request_Http;
    }

}