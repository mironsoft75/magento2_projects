<?php
namespace Codilar\Rapnet\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Customer;

class UrlRewrite implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;
    /**
     * @var Customer
     */
    private $customerModel;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $_url;
    /**
     * @var \Magento\Framework\App\Response\Http
     */
    private $_redirect;
    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    private $_responseFactory;
    /**
     * @var Magento\Framework\App\RequestInterface
     */
    private $request;
    /**
     * @var \Codilar\Rapnet\Model\ResourceModel\UrlRewrite\CollectionFactory
     */
    private $collectionFactory;

    /**
     * CustomerAttributes constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param Customer $customerModel
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\App\Response\Http $redirect
     * @param \Magento\Framework\App\ResponseFactory $responseFactory
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Codilar\Rapnet\Model\ResourceModel\UrlRewrite\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        Customer $customerModel,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $redirect,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Codilar\Rapnet\Model\ResourceModel\UrlRewrite\CollectionFactory $collectionFactory
    ) {
        $this->_logger = $logger;
        $this->customerModel = $customerModel;
        $this->customerSession = $customerSession;
        $this->_url = $url;
        $this->_redirect = $redirect;
        $this->_responseFactory = $responseFactory;
        $this->request = $request;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $baseUrl = $this->_url->getBaseUrl();
        $requestUrl = $this->request->getPathInfo();
        $collection = $this->collectionFactory->create()->addFieldToFilter("request_path", $requestUrl)->getFirstItem()->getData();
        if (count($collection)) {
            $newUrl = $baseUrl.$collection['target_path'];
            $observer->getControllerAction()
                ->getResponse()
                ->setRedirect($newUrl);
        }
        return $this;
    }
}
