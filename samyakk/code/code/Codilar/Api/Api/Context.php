<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/1/18
 * Time: 1:31 PM
 */

namespace Codilar\Api\Api;

use Codilar\Api\Helper\Cookie;
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
     * @var StoreManagerInterface
     */
    private $storeManager;


    /**
     * Context constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param StoreManagerInterface $storeManager
     */
    function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        StoreManagerInterface $storeManager
    )
    {

        $this->cookieHelper = $cookieHelper;
        $this->request = $request;
        $this->response = $response;
        $this->customerSession = $customerSession;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->storeManager = $storeManager;
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
     * @return StoreManagerInterface
     */
    public function getStoreManager(): StoreManagerInterface
    {
        return $this->storeManager;
    }

}