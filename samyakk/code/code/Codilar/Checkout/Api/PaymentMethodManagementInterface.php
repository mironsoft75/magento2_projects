<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api;


interface PaymentMethodManagementInterface
{
    /**
     * @param \Codilar\Checkout\Api\Data\Quote\BillingAddressInterface $address
     * @return \Codilar\Checkout\Api\Data\PaymentMethodOptionInterface[]
     */
    public function getPaymentMethods($address);
}