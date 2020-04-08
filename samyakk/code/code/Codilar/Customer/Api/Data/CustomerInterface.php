<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Customer\Api\Data;


interface CustomerInterface
{
    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getFirstname();

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname);

    /**
     * @return string
     */
    public function getLastname();

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname);

    /**
     * @return boolean
     */
    public function getIsPasswordChanged();

    /**
     * @param boolean $isPasswordChanged
     * @return $this
     */
    public function setIsPasswordChanged($isPasswordChanged);

    /**
     * @return string
     */
    public function getCurrentPassword();

    /**
     * @param string $currentPassword
     * @return $this
     */
    public function setCurrentPassword($currentPassword);

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
    public function getRepeatNewPassword();

    /**
     * @param string $repeatNewPassword
     * @return $this
     */
    public function setRepeatNewPassword($repeatNewPassword);
}