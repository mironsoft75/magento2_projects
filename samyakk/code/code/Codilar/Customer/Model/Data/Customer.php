<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Customer\Model\Data;


use Codilar\Customer\Api\Data\CustomerInterface;
use Magento\Framework\DataObject;

class Customer extends DataObject implements CustomerInterface
{

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getData('email');
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        return $this->setData('email', $email);
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->getData('firstname');
    }

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname)
    {
        return $this->setData('firstname', $firstname);
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->getData('lastname');
    }

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname)
    {
        return $this->setData('lastname', $lastname);
    }

    /**
     * @return boolean
     */
    public function getIsPasswordChanged()
    {
        return $this->getData('is_password_changed');
    }

    /**
     * @param boolean $isPasswordChanged
     * @return $this
     */
    public function setIsPasswordChanged($isPasswordChanged)
    {
        return $this->setData('is_password_changed', $isPasswordChanged);
    }

    /**
     * @return string
     */
    public function getCurrentPassword()
    {
        return $this->getData('current_password');
    }

    /**
     * @param string $currentPassword
     * @return $this
     */
    public function setCurrentPassword($currentPassword)
    {
        return $this->setData('current_password', $currentPassword);
    }

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
    public function getRepeatNewPassword()
    {
        return $this->getData('repeat_new_password');
    }

    /**
     * @param string $repeatNewPassword
     * @return $this
     */
    public function setRepeatNewPassword($repeatNewPassword)
    {
        return $this->setData('repeat_new_password', $repeatNewPassword);
    }
}