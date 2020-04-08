<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\ResetPassword\Api;


interface RequestResetPasswordInterface
{
    /**
     * @param \Codilar\ResetPassword\Api\Data\RequestResetPasswordDataInterface $data
     * @return \Codilar\Customer\Api\Data\AbstractResponseInterface
     */
    public function resetPassword($data);

    /**
     * @param string $token
     * @return \Codilar\Customer\Api\Data\AbstractResponseInterface
     */
    public function checkToken($token);

    /**
     * @param \Codilar\ResetPassword\Api\Data\ResetPasswordDataInterface $data
     * @return \Codilar\Customer\Api\Data\AbstractResponseInterface
     */
    public function confirmResetPassword($data);
}