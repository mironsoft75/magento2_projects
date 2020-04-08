<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Plugin\Payment\Method\CashOnDelivery;

use Codilar\Shipping\Model\Config as ShippingConfig;
use Magento\Backend\Model\Auth\Session as BackendSession;
use Magento\OfflinePayments\Model\Cashondelivery;

class Available
{

    /**
     * @var BackendSession
     */
    protected $backendSession;
    /**
     * @var ShippingConfig
     */
    private $shippingConfig;

    /**
     * @param BackendSession $backendSession
     * @param ShippingConfig $shippingConfig
     */
    public function __construct(
        BackendSession $backendSession,
        ShippingConfig $shippingConfig
    ) {
        $this->backendSession = $backendSession;
        $this->shippingConfig = $shippingConfig;
    }

    /**
     * @param Cashondelivery $subject
     * @param callable $proceed
     * @param $quote
     * @return bool
     */
    public function aroundIsAvailable(Cashondelivery $subject, callable $proceed, $quote = null)
    {
        // Do not remove payment method for admin
        if ($this->backendSession->isLoggedIn()) {
            $result = $proceed($quote);
            return $result;
        }

        if ($quote) {
            $zipcode = $quote->getShippingAddress()->getPostCode();
            $allowedZipcodes = array_column($this->shippingConfig->getAllowedZipcodes(), 'zipcode');
            if (in_array($zipcode, $allowedZipcodes)) {
                return true;
            } else {
                return false;
            }
        } else {
            return $proceed($quote);
        }
    }
}
