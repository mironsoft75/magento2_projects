<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\ResetPassword\Api\Data;


interface ResetPasswordDataInterface
{
    /**
     * @return string
     */
    public function getNewPassword();

    /**
     * @param string $newPassword
     * @return $this
     */
    public function setNewPassword($newPassword);

    /**
     * @return string
     */
    public function getConfirmNewPassword();

    /**
     * @param string $confirmNewPassword
     * @return $this
     */
    public function setConfirmNewPassword($confirmNewPassword);

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token);
}