<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 17/6/19
 * Time: 12:39 PM
 */

namespace Codilar\ProductImageAndVideos\Observer;

use Codilar\ProductImageAndVideos\Helper\Data;
use Magento\Customer\Model\Context;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlFactory;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class RestrictWebsite
 * @package Codilar\ProductImageAndVideos\Observer
 */
class RestrictWebsite implements ObserverInterface
{
    /**
     * @var UrlInterface
     */
    private $urlInterface;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Codilar\ProductImageAndVideos\Helper\Data $helper
     */
    private $helper;
    /**
     * @var ActionFlag  $actionFlag
     */
    private $actionFlag;

    /**
     * RestrictWebsite constructor.
     * @param ManagerInterface $eventManager
     * @param Http $response
     * @param UrlFactory $urlFactory
     * @param \Magento\Framework\App\Http\Context $context
     * @param ActionFlag $actionFlag
     * @param UrlInterface $urlInterface
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     */
    public function __construct(
        ManagerInterface $eventManager,
        Http $response,
        UrlFactory $urlFactory,
        \Magento\Framework\App\Http\Context $context,
        ActionFlag $actionFlag,
        UrlInterface $urlInterface,
        StoreManagerInterface $storeManager,
        Data $helper
    )
    {
        $this->response = $response;
        $this->urlFactory = $urlFactory;
        $this->context = $context;
        $this->actionFlag = $actionFlag;
        $this->urlInterface = $urlInterface;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $baseUrlAction = array('cms_index_index');
        $allowedRoutesBeforeLogin = $this->helper->getAllowedUrlsBeforeLogin();
        $allowedRoutesAfterLogin = $this->helper->getAllowedUrlsAfterLogin();
        $allowedRoutesBeforeLogin = explode(',', $allowedRoutesBeforeLogin);
        $allowedRoutesAfterLogin = explode(',', $allowedRoutesAfterLogin);
        $request = $observer->getEvent()->getRequest();
        /** @var bool $isCustomerLoggedIn */
        $isCustomerLoggedIn = $this->context->getValue(Context::CONTEXT_AUTH);
        /** @var string $actionFullName */
        $actionFullName = strtolower($request->getFullActionName());
        if ($this->helper->getUrlRestrictionEnabled()) {
            if (!$isCustomerLoggedIn && !in_array($actionFullName, $allowedRoutesBeforeLogin)) {
                $this->response->setRedirect($this->urlFactory->create()->getUrl('customer/account/login'));
            } elseif ($isCustomerLoggedIn && !in_array($actionFullName, $allowedRoutesAfterLogin) &&
                !in_array($actionFullName, $baseUrlAction)) {
                $this->response->setRedirect($this->storeManager->getStore()->getBaseUrl());
            }
        }

    }
}