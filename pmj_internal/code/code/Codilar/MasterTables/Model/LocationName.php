<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 12:36 PM
 */

namespace Codilar\MasterTables\Model;

use Codilar\MasterTables\Api\Data\LocationNameInterface;

class LocationName extends \Magento\Framework\Model\AbstractModel implements LocationNameInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_location_name';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_location_name';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_location_name_collection';

    protected function _construct()
    {
        $this->_init('Codilar\MasterTables\Model\ResourceModel\LocationName');
    }

    /**
     * @return string
     */
    public function getLocationId()
    {
        return $this->getData(self::LOCATION_ID);

    }

    /**
     * @param $locationId
     * @return LocationName|mixed
     */
    public function setLocationId($locationId)
    {
        return $this->setData(self::LOCATION_ID, $locationId);
    }

    /**
     * @return mixed
     */
    public function getLocationName()
    {
        return $this->getData(self::LOCATION_NAME);

    }

    /**
     * @param $locationName
     * @return LocationName|mixed
     */
    public function setLocationName($locationName)
    {
        return $this->setData(self::LOCATION_NAME, $locationName);

    }

    /**
     * @return mixed
     */
    public function getDept()
    {
        return $this->getData(self::DEPT);

    }

    /**
     * @param $dept
     * @return LocationName|mixed
     */
    public function setDept($dept)
    {
        return $this->setData(self::DEPT, $dept);

    }

    /**
     * @return mixed
     */
    public function getShowAsInstock()
    {
        return $this->getData(self::SHOW_AS_INSTOCK);

    }

    /**
     * @param $showAsInstock
     * @return LocationName|mixed
     */
    public function setShowAsInstock($showAsInstock)
    {
        return $this->setData(self::SHOW_AS_INSTOCK, $showAsInstock);

    }

    /**
     * @return mixed
     */
    public function getCustomerFriendlyLocationName()
    {
        return $this->getData(self::CUSTOMER_FRIENDLY_LOCATION_NAME);
    }

    /**
     * @param $customerFriendlyLocationName
     * @return LocationName|mixed
     */
    public function setCustomerFriendlyLocationName($customerFriendlyLocationName)
    {
        return $this->setData(self::CUSTOMER_FRIENDLY_LOCATION_NAME, $customerFriendlyLocationName);

    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);

    }

    /**
     * @param $createdAt
     * @return LocationName|mixed
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);

    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);

    }

    /**
     * @param $updatedAt
     * @return LocationName|mixed
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);

    }
}