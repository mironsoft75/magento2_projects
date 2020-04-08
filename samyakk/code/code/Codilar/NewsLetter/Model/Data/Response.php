<?php
/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\NewsLetter\Model\Data;


use Codilar\NewsLetter\Api\Data\ResponseInterface;
use Magento\Framework\DataObject;

class Response extends DataObject implements ResponseInterface
{

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->getData("status");
    }

    /**
     * @param boolean $status
     * @return \Codilar\NewsLetter\Api\Data\ResponseInterface
     */
    public function setStatus($status)
    {
        return $this->setData("status", $status);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getData("message");
    }

    /**
     * @param string $message
     * @return \Codilar\NewsLetter\Api\Data\ResponseInterface
     */
    public function setMessage($message)
    {
        return $this->setData("message", $message);
    }
}