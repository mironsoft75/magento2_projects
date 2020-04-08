<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Shipping\Model\Data;


use Codilar\Shipping\Api\Data\NotifyResponseInterface;
use Magento\Framework\DataObject;

class NotifyResponse extends DataObject implements NotifyResponseInterface
{

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return (bool)$this->getData('status');
    }

    /**
     * @param boolean $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->setData('status', $status);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return (string)$this->getData('message');
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        return $this->setData('message', $message);
    }
}