<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Customer\Api;


interface AccountManagementInterface
{
    /**
     * @param string $token
     * @param string $provider
     * @return \Codilar\Customer\Api\Data\LoginResponseInterface
     */
    public function socialLogin($token, $provider = 'firebase');
}