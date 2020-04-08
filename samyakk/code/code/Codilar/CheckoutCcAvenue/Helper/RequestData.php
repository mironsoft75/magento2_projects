<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutCcAvenue\Helper;

use Codilar\CheckoutCcAvenue\Model\Config;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

class RequestData
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var CountryFactory
     */
    private $countryFactory;
    /**
     * @var Crypto
     */
    private $crypto;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * RequestData constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param StoreManagerInterface $storeManager
     * @param CountryFactory $countryFactory
     * @param Crypto $crypto
     * @param Config $config
     * @param DateTime $dateTime
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        StoreManagerInterface $storeManager,
        CountryFactory $countryFactory,
        Crypto $crypto,
        Config $config,
        DateTime $dateTime
    )
    {
        $this->orderRepository = $orderRepository;
        $this->storeManager = $storeManager;
        $this->countryFactory = $countryFactory;
        $this->crypto = $crypto;
        $this->config = $config;
        $this->dateTime = $dateTime;
    }


    /**
     * @param int $orderId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRequestData($orderId) {
        $requestData = [];
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        $date = $this->dateTime->date();
        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
        $order = $this->orderRepository->get($orderId);
        /** @var \Magento\Sales\Api\Data\OrderAddressInterface $shippingAddress */
        $shippingAddress = $this->getShippingAddress($order);
        /** @var \Magento\Sales\Api\Data\OrderAddressInterface $billingAddress */
        $billingAddress = $this->getBillingAddress($order);
        $requestData = [
            'tid' => strtotime($date),
            'merchant_id' => $this->config->getMerchantId(),
            'order_id' => $order->getIncrementId(),
            'amount' => number_format($order->getGrandTotal(),2, ".", ""),
            'currency' => $order->getOrderCurrencyCode(),
            'redirect_url' => $baseUrl .'checkoutccavenue/response/index',
            'cancel_url' => $baseUrl .'checkoutccavenue/response/index',
            'language' => 'EN',
            'billing_name' => $billingAddress->getFirstname()." ".$billingAddress->getLastname(),
            'billing_address' => implode(" ", $billingAddress->getStreet()),
            'billing_city' => $billingAddress->getCity(),
            'billing_state' => $billingAddress->getRegion(),
            'billing_zip' => $billingAddress->getPostcode(),
            'billing_country' => $this->countryFactory->create()->loadByCode($billingAddress->getCountryId())->getName(),
            'billing_tel' => $billingAddress->getTelephone(),
            'billing_email' => $billingAddress->getEmail(),
            'delivery_name' => $shippingAddress->getFirstname()." ".$shippingAddress->getLastname(),
            'delivery_address' => implode(" ", $shippingAddress->getStreet()),
            'delivery_city' => $shippingAddress->getCity(),
            'delivery_state' => $shippingAddress->getRegion(),
            'delivery_zip' => $shippingAddress->getPostcode(),
            'delivery_country' => $this->countryFactory->create()->loadByCode($shippingAddress->getCountryId())->getName(),
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
            $merchant_data.=$key.'='.$value.'&';
        }

        $encrypted_data=$this->crypto->encrypt($merchant_data, $this->config->getSecretKey());
        return $encrypted_data;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return \Magento\Sales\Api\Data\OrderAddressInterface|null
     */
    protected function getBillingAddress($order) {
        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
       return $order->getBillingAddress();
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return \Magento\Sales\Api\Data\OrderAddressInterface|null
     */
    protected function getShippingAddress($order) {
        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
        return $order->getShippingAddress();
    }

}