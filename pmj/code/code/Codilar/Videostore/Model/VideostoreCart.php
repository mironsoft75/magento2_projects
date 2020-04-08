<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 2:50 PM
 */

namespace Codilar\Videostore\Model;
use Codilar\Videostore\Api\Data\VideostoreCartInterface;

class VideostoreCart extends \Magento\Framework\Model\AbstractModel implements VideostoreCartInterface, \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_videostore_cart';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_videostore_cart';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_videostore_cart';

    protected function _construct()
    {
        $this->_init('\Codilar\Videostore\Model\ResourceModel\VideostoreCart');
    }

    /**
     * @return mixed
     */
    public function getVideostoreCartId()
    {
        return $this->getData(self::VIDEOSTORE_CART_ID);
    }

    /**
     * @param $cartId
     * @return mixed
     */
    public function setVideostoreCartId($cartId)
    {
        return $this->setData(self::VIDEOSTORE_CART_ID, $cartId);
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * @param $productId
     * @return mixed
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @return mixed
     */
    public function getVideostoreCustomerId()
    {
        return $this->getData(self::VIDEOSTORE_CUSTOMER_ID);
    }

    /**
     * @param $videostoreCustomerId
     * @return mixed
     */
    public function setVideostoreCustomerId($videostoreCustomerId)
    {
        return $this->setData(self::VIDEOSTORE_CUSTOMER_ID, $videostoreCustomerId);
    }

    /**
     * @return mixed
     */
    public function getVideostoreCustomerSessionId()
    {
        return $this->getData(self::VIDEOSTORE_CUSTOMER_SESSION_ID);
    }

    /**
     * @param $videostoreCustomerSessionId
     * @return mixed
     */
    public function setVideostoreCustomerSessionId($videostoreCustomerSessionId)
    {
        return $this->setData(self::VIDEOSTORE_CUSTOMER_SESSION_ID, $videostoreCustomerSessionId);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}