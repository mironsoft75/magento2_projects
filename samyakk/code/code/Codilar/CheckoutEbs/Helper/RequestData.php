<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/9/19
 * Time: 12:38 PM
 */

namespace Codilar\CheckoutEbs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Codilar\CheckoutEbs\Model\Config;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class RequestData
 *
 * @package Codilar\CheckoutEbs\Helper
 */
class RequestData extends AbstractHelper
{
    /**
     * OrderRepositoryInterface
     *
     * @var OrderRepositoryInterface
     */
    private $_orderRepository;
    /**
     * StoreManagerInterface
     *
     * @var StoreManagerInterface
     */
    private $_storeManager;
    /**
     * CountryFactory
     *
     * @var CountryFactory
     */
    private $_countryFactory;
    /**
     * Crypto
     *
     * @var Crypto
     */
    private $_crypto;
    /**
     * Config
     *
     * @var Config
     */
    private $_config;
    /**
     * DateTime
     *
     * @var DateTime
     */
    private $_dateTime;

    /**
     * RequestData constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param StoreManagerInterface $storeManager
     * @param CountryFactory $countryFactory
     * @param Crypto $crypto
     * @param Config $config
     * @param DateTime $dateTime
     * @param Context $context
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        StoreManagerInterface $storeManager,
        CountryFactory $countryFactory,
        Crypto $crypto,
        Config $config,
        DateTime $dateTime,
        Context $context
    ) {
        $this->_orderRepository = $orderRepository;
        $this->_storeManager = $storeManager;
        $this->_countryFactory = $countryFactory;
        $this->_crypto = $crypto;
        $this->_config = $config;
        $this->_dateTime = $dateTime;
        parent::__construct($context);
    }

    /**
     * GetRequestData
     *
     * @param int $orderId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRequestData($orderId)
    {
        $requestData = [];
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
        $date = $this->_dateTime->date();
        /**
         * OrderInterface
         *
         * @var \Magento\Sales\Api\Data\OrderInterface $order
         */
        $order = $this->_orderRepository->get($orderId);
        /**
         * OrderAddressInterface
         *
         * @var \Magento\Sales\Api\Data\OrderAddressInterface $shippingAddress
         */
        $shippingAddress = $this->getShippingAddress($order);
        /**
         * OrderAddressInterface
         *
         * @var \Magento\Sales\Api\Data\OrderAddressInterface $billingAddress
         */
        $billingAddress = $this->getBillingAddress($order);
        $requestData = [
            'tid' => strtotime($date),
            'account_name' => $this->_config->getAccountName(),
            'order_id' => $order->getIncrementId(),
            'amount' => number_format($order->getGrandTotal(), 2, ".", ""),
            'currency' => $order->getOrderCurrencyCode(),
            'redirect_url' => $baseUrl . 'checkoutebs/response/index',
            'cancel_url' => $baseUrl . 'checkoutebs/response/index',
            'language' => 'EN',
            'billing_name' => $billingAddress->getFirstname() . " " .
                $billingAddress->getLastname(),
            'billing_address' => implode(" ", $billingAddress->getStreet()),
            'billing_city' => $billingAddress->getCity(),
            'billing_state' => $billingAddress->getRegion(),
            'billing_zip' => $billingAddress->getPostcode(),
            'billing_country' => $this->_countryFactory->create()
                ->loadByCode($billingAddress->getCountryId())->getName(),
            'billing_tel' => $billingAddress->getTelephone(),
            'billing_email' => $billingAddress->getEmail(),
            'delivery_name' => $shippingAddress->getFirstname() . " " .
                $shippingAddress->getLastname(),
            'delivery_address' => implode(" ", $shippingAddress->getStreet()),
            'delivery_city' => $shippingAddress->getCity(),
            'delivery_state' => $shippingAddress->getRegion(),
            'delivery_zip' => $shippingAddress->getPostcode(),
            'delivery_country' => $this->_countryFactory->create()
                ->loadByCode($shippingAddress->getCountryId())->getName(),
            'delivery_tel' => $shippingAddress->getTelephone(),
            'merchant_param1' => '',
            'merchant_param2' => '',
            'merchant_param3' => '',
            'merchant_param4' => '',
            'merchant_param5' => '',
            'promo_code' => '',
            'customer_identifier' => ''
        ];

        $merchant_data = '';
        foreach ($requestData as $key => $value) {
            $merchant_data .= $key . '=' . $value . '&';
        }

        return $this->_crypto
            ->encrypt($merchant_data, $this->_config->getSecretKey());
    }

    /**
     * GetBillingAddress
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return \Magento\Sales\Api\Data\OrderAddressInterface|null
     */
    protected function getBillingAddress($order)
    {
        /**
         * OrderInterface
         *
         * @var \Magento\Sales\Api\Data\OrderInterface $order
         */
        return $order->getBillingAddress();
    }

    /**
     * GetShippingAddress
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return \Magento\Sales\Api\Data\OrderAddressInterface|null
     */
    protected function getShippingAddress($order)
    {
        /**
         * OrderInterface
         * @var \Magento\Sales\Api\Data\OrderInterface $order
         */
        return $order->getShippingAddress();
    }
}