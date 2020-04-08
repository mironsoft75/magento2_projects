<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/9/19
 * Time: 11:58 AM
 */

namespace Codilar\CheckoutEbs\Block;

use Codilar\CheckoutEbs\Helper\RequestData;
use Codilar\CheckoutEbs\Model\Config;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\UrlInterface;

/**
 * Class Form
 *
 * @package Codilar\CheckoutEbs\Block
 */
class Form extends Template
{
    /**
     * Config
     *
     * @var Config
     */
    private $_config;
    /**
     * OrderRepositoryInterface
     *
     * @var OrderRepositoryInterface
     */
    private $_orderRepository;
    /**
     * RequestData
     *
     * @var RequestData
     */
    private $_requestData;
    /**
     * Url Interface
     *
     * @var UrlInterface
     */
    private $_urlInterface;


    /**
     * Form constructor.
     *
     * @param Template\Context $context
     * @param Config $config
     * @param OrderRepositoryInterface $orderRepository
     * @param RequestData $requestData
     * @param UrlInterface $urlInterface
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Config $config,
        OrderRepositoryInterface $orderRepository,
        RequestData $requestData,
        UrlInterface $urlInterface,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_config = $config;
        $this->_orderRepository = $orderRepository;
        $this->_requestData = $requestData;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * Get submitUrl
     *
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->_config->getPaymentUrl();
    }


    /**
     * Get RedirectingMessage
     *
     * @return string
     */
    public function getRedirectingMessage()
    {
        return $this->_config->getRedirectMessage();
    }

    /**
     * Get Order
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function getOrder()
    {
        $id = $this->getOrderId();
        return $this->_orderRepository->get($id);
    }

    /**
     * Get ReturnUrl
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->_urlInterface->getUrl('checkoutebs/response/index');
    }

    /**
     * Get OrderId
     *
     * @return mixed|string
     */
    public function getOrderId()
    {
        return $this->getRequest()->getParam('order_id');

    }

    /**
     * Get ReferenceNumber
     *
     * @return null|string
     */
    public function getReferenceNumber()
    {
        return $this->getOrder()->getIncrementId();
    }

    /**
     * Get BillingAddress
     *
     * @return \Magento\Sales\Api\Data\OrderAddressInterface|null
     */
    public function getBillingAddress()
    {
        $order = $this->getOrder();
        return $order->getBillingAddress();
    }

    /**
     * Get BillingRegion
     *
     * @return null|string
     */
    public function getBillingRegion()
    {
        return $this->getBillingAddress()->getRegion();
    }

    /**
     * Get BillingPostalCode
     *
     * @return string
     */
    public function getBillingPostalCode()
    {
        return $this->getBillingAddress()->getPostcode();
    }

    /**
     * Get BillingCountryCode
     *
     * @return string
     */
    public function getBillingCountryCode()
    {
        return $this->getBillingAddress()->getCountryId();
    }

    /**
     * Get BillingPhoneNumber
     *
     * @return string
     */
    public function getBillingPhoneNumber()
    {
        return $this->getBillingAddress()->getTelephone();

    }

    /**
     * Get BillingEmail
     *
     * @return string
     */
    public function getBillingEmail()
    {
        return $this->getBillingAddress()->getEmail();

    }

    /**
     * Get BillingCity
     *
     * @return string
     */
    public function getBillingCity()
    {
        return $this->getBillingAddress()->getCity();

    }

    /**
     * Get CustomerFullName
     *
     * @return string
     */
    public function getCustomerFullName()
    {
        return $this->getBillingAddress()->getFirstname() . " " .
            $this->getBillingAddress()->getLastname();

    }

    /**
     * Get Amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->getOrder()->getBaseGrandTotal();
    }

    /**
     * Get AccountId
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->_config->getAccountId();
    }

    /**
     * Get SecreteKey
     *
     * @return string
     */
    public function getSecreteKey()
    {
        return $this->_config->getSecretKey();
    }

    /**
     * Get Mode
     *
     * @return string
     */
    public function getMode()
    {
        $mode = $this->_config->getMode();
        return ($mode == 1) ? "LIVE" : "TEST";
    }


    /**
     * Get Billing Street
     *
     * @return string
     */
    public function getBillingStreet()
    {
        $streets = $this->getBillingAddress()->getStreet();
        $billingStreet = "";
        if (is_array($streets)) {
            foreach ($streets as $street) {
                $billingStreet = $billingStreet . $street;
            }
        }
        return $billingStreet;
    }

    /**
     * Get Channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->_config->getChannel();
    }

}