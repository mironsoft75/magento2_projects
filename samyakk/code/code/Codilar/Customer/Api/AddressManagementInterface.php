<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Customer\Api;


interface AddressManagementInterface
{
    /**
     * @param \Codilar\Customer\Api\Data\AddressInterface
     * @return \Codilar\Customer\Api\Data\AbstractResponseInterface
     */
    public function updateShippingAddress($address);
}