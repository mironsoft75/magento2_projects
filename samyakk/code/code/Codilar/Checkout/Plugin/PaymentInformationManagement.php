<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Plugin;

use Magento\Checkout\Api\Data\PaymentDetailsInterface;
use Magento\Checkout\Api\PaymentInformationManagementInterface as Subject;
use Codilar\Checkout\Helper\Payment as PaymentHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\QuoteIdMaskFactory;

class PaymentInformationManagement
{
    /**
     * @var PaymentHelper
     */
    private $paymentHelper;
    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * PaymentInformationManagement constructor.
     * @param PaymentHelper $paymentHelper
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        QuoteIdMaskFactory $quoteIdMaskFactory
    )
    {
        $this->paymentHelper = $paymentHelper;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * @param Subject $subject
     * @param callable $proceed
     * @param string $cartId
     * @return PaymentDetailsInterface
     */
    public function aroundGetPaymentInformation(
        $subject,
        callable $proceed,
        $cartId
    )
    {
        /** @var PaymentDetailsInterface $paymentDetails */
        $paymentDetails = $proceed($cartId);
        $paymentMethodOptions = [];
        foreach ($paymentDetails->getPaymentMethods() as $paymentMethod) {
            try {
                $paymentMethodOption = $this->paymentHelper->getPaymentMethodOptionByCode($paymentMethod->getCode());
                $this->paymentHelper->addCsrfTokenForPaymentMethod($paymentMethod->getCode(), $cartId);
                $paymentMethodOptions[] = $paymentMethodOption;
            } catch (NoSuchEntityException $e) {
                continue;
            }
        }
        $paymentDetails->getExtensionAttributes()->setPaymentMethodOptions($paymentMethodOptions);
        return $paymentDetails;
    }
}