<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model;


use Codilar\Checkout\Api\PaymentMethodManagementInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use \Magento\Payment\Model\Config;
use Codilar\Api\Helper\Customer;
use Codilar\Checkout\Helper\Payment as PaymentHelper;

class PaymentMethodManagement implements PaymentMethodManagementInterface
{
    /**
     * @var Config
     */
    private $paymentConfig;
    /**
     * @var PaymentHelper
     */
    private $paymentHelper;
    /**
     * @var Customer
     */
    private $customerHelper;
    /**
     * @var \Magento\Quote\Api\PaymentMethodManagementInterface
     */
    private $paymentMethodManagement;

    /**
     * PaymentMethodManagement constructor.
     * @param Config $paymentConfig
     * @param Customer $customerHelper
     * @param PaymentHelper $paymentHelper
     * @param \Magento\Quote\Api\PaymentMethodManagementInterface $paymentMethodManagement
     */
    public function __construct(
        Config $paymentConfig,
        Customer $customerHelper,
        PaymentHelper $paymentHelper,
        \Magento\Quote\Api\PaymentMethodManagementInterface $paymentMethodManagement
    )
    {
        $this->paymentConfig = $paymentConfig;
        $this->customerHelper = $customerHelper;
        $this->paymentHelper = $paymentHelper;
        $this->paymentMethodManagement = $paymentMethodManagement;
    }

    /**
     * @param \Codilar\Checkout\Api\Data\Quote\BillingAddressInterface $address
     * @return \Codilar\Checkout\Api\Data\PaymentMethodOptionInterface[]
     */
    public function getPaymentMethods($address)
    {
        /** @var \Magento\Quote\Model\Quote $activeQuote */
        $quote = $this->customerHelper->getActiveQuote();
        $paymentMethods = $this->paymentMethodManagement->getList($quote->getId());
        $paymentMethodOptions = [];
        foreach ($paymentMethods as $paymentMethod) {
            try {
                $paymentMethodOption = $this->paymentHelper->getPaymentMethodOptionByCode($paymentMethod->getCode());
                $this->paymentHelper->addCsrfTokenForPaymentMethod($paymentMethod->getCode(), $quote->getId());
                $paymentMethodOptions[] = $paymentMethodOption;
            } catch (NoSuchEntityException $e) {
                continue;
            }
        }
        return $paymentMethodOptions;
    }
}