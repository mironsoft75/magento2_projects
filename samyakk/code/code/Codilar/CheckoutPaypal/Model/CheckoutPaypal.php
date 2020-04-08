<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Model;


use Codilar\CheckoutPaypal\Api\Data\CheckoutPaypalInterface;
use Magento\Framework\Model\AbstractModel;
use Codilar\CheckoutPaypal\Model\ResourceModel\CheckoutPaypal as ResourceModel;

class CheckoutPaypal extends AbstractModel implements CheckoutPaypalInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @return string
     */
    public function getPaypalPayId()
    {
        return $this->getData(self::PAYPAL_PAY_ID);
    }

    /**
     * @param string $paypalPayId
     * @return $this
     */
    public function setPaypalPayId($paypalPayId)
    {
        return $this->setData(self::PAYPAL_PAY_ID, $paypalPayId);
    }

    /**
     * @return string
     */
    public function getPaypalToken()
    {
        return $this->getData(self::PAYPAL_TOKEN);
    }

    /**
     * @param string $paypalToken
     * @return $this
     */
    public function setPaypalToken($paypalToken)
    {
        return $this->setData(self::PAYPAL_TOKEN, $paypalToken);
    }
}