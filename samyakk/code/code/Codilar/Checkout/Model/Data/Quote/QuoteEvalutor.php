<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model\Data\Quote;

use Codilar\Checkout\Api\Data\Quote\QuoteEvalutorInterface;
use Magento\Framework\DataObject;

class QuoteEvalutor extends DataObject implements QuoteEvalutorInterface
{

    /**
     * @param boolean $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->setData('status', $status);
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->getData('status');
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        return $this->setData('message', $message);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getData('message');
    }

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        return $this->setData('order_id', $orderId);
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->getData('order_id');
    }

    /**
     * @param string $incrementId
     * @return $this
     */
    public function setIncrementId($incrementId)
    {
        return $this->setData('increment_id', $incrementId);
    }

    /**
     * @return string mixed
     */
    public function getIncrementId()
    {
        return $this->getData('increment_id');
    }
}
