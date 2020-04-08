<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\ResetPassword\Model\Data;


use Codilar\ResetPassword\Api\Data\RequestResetPasswordDataInterface;
use Magento\Framework\DataObject;

class RequestResetPasswordData extends DataObject implements RequestResetPasswordDataInterface
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
}