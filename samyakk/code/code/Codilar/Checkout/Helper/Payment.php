<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Helper;


use Codilar\Checkout\Api\Data\PaymentMethodOptionInterface;
use Codilar\Checkout\Api\Data\PaymentMethodOptionInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Payment\Helper\Data as PaymentHelper;

class Payment
{
    /**
     * @var PaymentHelper
     */
    private $paymentHelper;
    /**
     * @var PaymentMethodOptionInterfaceFactory
     */
    private $paymentMethodOptionFactory;

    /**
     * @var PaymentMethodOptionInterface[]
     */
    private $paymentMethodOptions = [];

    /**
     * Payment constructor.
     * @param PaymentHelper $paymentHelper
     * @param PaymentMethodOptionInterfaceFactory $paymentMethodOptionFactory
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        PaymentMethodOptionInterfaceFactory $paymentMethodOptionFactory
    )
    {
        $this->paymentHelper = $paymentHelper;
        $this->paymentMethodOptionFactory = $paymentMethodOptionFactory;
    }

    /**
     * @param string $code
     * @return PaymentMethodOptionInterface
     * @throws NoSuchEntityException
     */
    public function getPaymentMethodOptionByCode($code)
    {
        if (!array_key_exists($code, $this->paymentMethodOptions)) {
            try {
                $paymentMethod = $this->paymentHelper->getMethodInstance($code);
                $paymentMethodOption = $this->paymentMethodOptionFactory->create();
                $paymentMethodOption->setCode($paymentMethod->getCode())
                    ->setTitle($paymentMethod->getTitle())
                    ->setIsOnline((boolean)$paymentMethod->getIsOnline())
                    ->setInstructions((string)$paymentMethod->getInstructions())
                    ->setRedirectUrl((string)$paymentMethod->getRedirectUrl())
                    ->setCsrfToken($paymentMethod->getCsrfToken());
                $this->paymentMethodOptions[$code] = $paymentMethodOption;
            } catch (LocalizedException $e) {
                throw NoSuchEntityException::singleField('code', $code);
            }
        }
        return $this->paymentMethodOptions[$code];
    }

    /**
     * @param string $code
     * @param string $salt
     * @return PaymentMethodOptionInterface
     * @throws NoSuchEntityException
     */
    public function addCsrfTokenForPaymentMethod($code, $salt = '')
    {
        $paymentMethod = $this->getPaymentMethodOptionByCode($code);
        if (!$paymentMethod->getCsrfToken()) {
            $paymentMethod->setCsrfToken(
                sha1(
                    $salt .
                    $paymentMethod->getCode() .
                    $paymentMethod->getInstructions() .
                    $paymentMethod->getRedirectUrl() .
                    $paymentMethod->getIsOnline()
                )
            );
        }
        return $paymentMethod;
    }
}