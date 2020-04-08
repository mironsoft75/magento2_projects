<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\ResetPassword\Model\Data;


use Codilar\ResetPassword\Api\Data\ResetPasswordDataInterface;
use Magento\Framework\DataObject;

class ResetPasswordData extends DataObject implements ResetPasswordDataInterface
{

    /**
     * @return string
     */
    public function getNewPassword()
    {
        return $this->getData('new_password');
    }

    /**
     * @param string $newPassword
     * @return $this
     */
    public function setNewPassword($newPassword)
    {
        return $this->setData('new_password', $newPassword);
    }

    /**
     * @return string
     */
    public function getConfirmNewPassword()
    {
        return $this->getData('confirm_new_password');
    }

    /**
     * @param string $confirmNewPassword
     * @return $this
     */
    public function setConfirmNewPassword($confirmNewPassword)
    {
        return $this->setData('confirm_new_password', $confirmNewPassword);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->getData('token');
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        return $this->setData('token', $token);
    }
}