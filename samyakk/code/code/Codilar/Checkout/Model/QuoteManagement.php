<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model;

use Codilar\Api\Helper\Customer;
use Codilar\Checkout\Api\Data\Quote\QuoteEvalutorInterface;
use Codilar\Checkout\Api\QuoteManagementInterface;
use Codilar\Checkout\Helper\Address;
use Codilar\Core\Helper\GeoLocation;
use Codilar\Store\Api\StoreRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class QuoteManagement implements QuoteManagementInterface
{
    const PAYPALPAYMENTMETHOD = 'codilar_checkout_paypal';

    /**
     * @var Customer
     */
    private $customerHelper;
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var PaymentHelper
     */
    private $paymentHelper;
    /**
     * @var CartManagementInterface
     */
    private $cartManagement;
    /**
     * @var QuoteEvalutorInterface
     */
    private $quoteEvalutor;
    /**
     * @var AddressInterface
     */
    private $addressInterface;
    /**
     * @var Address
     */
    private $addressHelper;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var GeoLocation
     */
    private $geoLocation;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * QuoteManagement constructor.
     * @param Customer $customerHelper
     * @param CartRepositoryInterface $cartRepository
     * @param PaymentHelper $paymentHelper
     * @param CartManagementInterface $cartManagement
     * @param QuoteEvalutorInterface $quoteEvalutor
     * @param AddressInterface $addressInterface
     * @param Address $addressHelper
     * @param StoreManagerInterface $storeManager
     * @param StoreRepositoryInterface $storeRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param GeoLocation $geoLocation
     * @param LoggerInterface $logger
     */
    public function __construct(
        Customer $customerHelper,
        CartRepositoryInterface $cartRepository,
        PaymentHelper $paymentHelper,
        CartManagementInterface $cartManagement,
        QuoteEvalutorInterface $quoteEvalutor,
        AddressInterface $addressInterface,
        Address $addressHelper,
        StoreManagerInterface $storeManager,
        StoreRepositoryInterface $storeRepository,
        OrderRepositoryInterface $orderRepository,
        GeoLocation $geoLocation,
        LoggerInterface $logger
    ) {
        $this->customerHelper = $customerHelper;
        $this->cartRepository = $cartRepository;
        $this->paymentHelper = $paymentHelper;
        $this->cartManagement = $cartManagement;
        $this->quoteEvalutor = $quoteEvalutor;
        $this->addressInterface = $addressInterface;
        $this->addressHelper = $addressHelper;
        $this->storeManager = $storeManager;
        $this->storeRepository = $storeRepository;
        $this->orderRepository = $orderRepository;
        $this->geoLocation = $geoLocation;
        $this->logger = $logger;
    }

    /**
     * @param \Codilar\Checkout\Api\Data\QuoteInterface $quote
     * @return QuoteEvalutorInterface
     * @throws LocalizedException
     */
    public function placeOrder($quote)
    {
        $customer = $this->customerHelper->getCustomerIdByToken(true);
        if ($quote->getEmail() != $customer->getEmail()) {
            throw new LocalizedException(__("Email did not match with customer"));
        }
        $currentCurrencyCode = $this->storeManager->getStore()->getCurrentCurrencyCode();
        $currencyCode = $quote->getCurrencyCode() == $currentCurrencyCode || $quote->getCurrencyCode() == "" ? $currentCurrencyCode : $quote->getCurrencyCode();

        if ($quote->getPaymentMethod() === self::PAYPALPAYMENTMETHOD && $currencyCode === "INR") {
            $currencyCodeByIp = $this->geoLocation->getCurrencyDetailsByIP($quote->getRemoteIp());
            if (isset($currencyCodeByIp['currency'])) {
                if ($currencyCodeByIp['currency'] != "INR") {
                    $currencyCode = $currencyCodeByIp['currency'];
                }
            } else {
                $this->logger->debug('CurrencyCodeByIP array does not contain currency key');
            }
        }
        $allowedCureencies = $this->storeRepository->getCurrencies()->getCurrencies();
        $illegalCurrencyCode = true;
        foreach ($allowedCureencies as $allowedCureency) {
            if ($allowedCureency->getCurrencyCode() === $currencyCode) {
                $illegalCurrencyCode = false;
                break;
            }
        }
        if ($illegalCurrencyCode) {
            throw new LocalizedException(__($currencyCode . " Currency is not allowed"));
        }

        /** @var \Magento\Quote\Model\Quote $activeQuote */
        $activeQuote = $this->customerHelper->getActiveQuote();
        $activeQuote->setCustomerEmail($quote->getEmail());
        $this->storeManager->getStore()->setCurrentCurrencyCode($currencyCode);
        $activeQuote->setQuoteCurrencyCode($currencyCode)->save();
        $activeQuote->getShippingAddress()->addData($this->addressHelper->getAddressArray($quote->getShippingAddress()));
        $activeQuote->getBillingAddress()->addData($this->addressHelper->getAddressArray($quote->getBillingAddress()));
        $activeQuote->getShippingAddress()
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($quote->getShippingMethod());
        $activeQuote->setPaymentMethod($quote->getPaymentMethod());
        $activeQuote->setInventoryProcessed(false);
        $activeQuote->setRemoteIp($quote->getRemoteIp());
        $activeQuote->getPayment()->importData(['method' => $quote->getPaymentMethod()]);
        try {
            $activeQuote->save();
            $activeQuote->collectTotals();
            $cart = $this->cartRepository->get($activeQuote->getId());
            $orderId = $this->cartManagement->placeOrder($cart->getId());
            $order = $this->orderRepository->get($orderId);
            $response = $this->quoteEvalutor->setStatus(true)
                ->setMessage("Order Placed Success")
                ->setOrderId($orderId)
                ->setIncrementId($order->getIncrementId());
        } catch (\Exception $e) {
            $response = $this->quoteEvalutor->setStatus(false)
                ->setMessage($e->getMessage());
        }
        $this->storeManager->getStore()->setCurrentCurrencyCode($currentCurrencyCode);
        return $response;
    }
}
