<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api;


interface QuoteManagementInterface
{
    /**
     * @param \Codilar\Checkout\Api\Data\QuoteInterface $quote
     * @return \Codilar\Checkout\Api\Data\Quote\QuoteEvalutorInterface
     */
    public function placeOrder($quote);
}