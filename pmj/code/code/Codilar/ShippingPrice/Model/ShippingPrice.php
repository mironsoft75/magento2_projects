<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/4/19
 * Time: 1:36 PM
 */

namespace Codilar\ShippingPrice\Model;

use Codilar\ShippingPrice\Api\Data\ShippingPriceInterface;

class ShippingPrice extends \Magento\Framework\Model\AbstractModel implements ShippingPriceInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'shipping_tablerate';

    /**
     * @var string
     */
    protected $_cacheTag = 'shipping_tablerate';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'shipping_tablerate_collection';

    protected function _construct()
    {
        $this->_init('Codilar\ShippingPrice\Model\ResourceModel\ShippingPrice');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function setId($Id)
    {
        return $this->setData(self::ID, $Id);
    }

    /**
     * @return mixed
     */
    public function getWebsiteId()
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     * @param $websiteId
     * @return mixed
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);

    }

    /**
     * @return mixed
     */
    public function getCountryId()
    {
        return $this->getData(self::DEST_COUNTRY_ID);

    }

    /**
     * @param $countryId
     * @return mixed
     */
    public function setCountryId($countryId)
    {
        return $this->setData(self::DEST_COUNTRY_ID, $countryId);

    }

    /**
     * @return mixed
     */
    public function getRegionId()
    {
        return $this->getData(self::DEST_REGION_ID);

    }

    /**
     * @param $regionId
     * @return mixed
     */
    public function setRegionId($regionId)
    {
        return $this->setData(self::DEST_REGION_ID, $regionId);

    }

    /**
     * @return mixed
     */
    public function getZipId()
    {
        return $this->getData(self::DEST_ZIP);
    }

    /**
     * @param $zipId
     * @return mixed
     */
    public function setZipId($zipId)
    {
        return $this->setData(self::DEST_ZIP, $zipId);
    }

    /**
     * @return mixed
     */
    public function getConditionName()
    {
        return $this->getData(self::CONDITION_NAME);

    }

    /**
     * @param $conditionName
     * @return mixed
     */

    public function setConditionName($conditionName)
    {
        return $this->setData(self::CONDITION_NAME, $conditionName);

    }

    /**
     * @return mixed
     */
    public function getConditionValue()
    {
        return $this->getData(self::CONDITION_VALUE);

    }

    /**
     * @param $conditionValue
     * @return mixed
     */
    public function setConditionValue($conditionValue)
    {
        return $this->setData(self::CONDITION_VALUE, $conditionValue);

    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * @param $price
     * @return mixed
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * @return mixed
     */
    public function getDays()
    {
        return $this->getData(self::DAYS);
    }

    /**
     * @param $days
     * @return mixed
     */
    public function setDays($days)
    {
        return $this->setData(self::DAYS, $days);

    }



}