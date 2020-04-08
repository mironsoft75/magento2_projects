<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 10:20 AM
 */

namespace Codilar\MasterTables\Api\Data;


Interface LocationNameInterface
{
    const LOCATION_ID = "location_id";
    const LOCATION_NAME = "location_name";
    const DEPT = "dept";
    const SHOW_AS_INSTOCK = "show_as_instock";
    const CUSTOMER_FRIENDLY_LOCATION_NAME = "customer_friendly_location_name";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";


    /**
     * @return string
     */
    public function getLocationId();

    /**
     * @param $locationId
     * @return mixed
     */
    public function setLocationId($locationId);

    /**
     * @return mixed
     */
    public function getLocationName();

    /**
     * @param $locationName
     * @return mixed
     */
    public function setLocationName($locationName);

    /**
     * @return mixed
     */
    public function getDept();

    /**
     * @param $dept
     * @return mixed
     */
    public function setDept($dept);

    /**
     * @return mixed
     */
    public function getShowAsInstock();

    /**
     * @param $showAsInstock
     * @return mixed
     */
    public function setShowAsInstock($showAsInstock);

    /**
     * @return mixed
     */
    public function getCustomerFriendlyLocationName();

    /**
     * @param $customerFriendlyLocationName
     * @return mixed
     */
    public function setCustomerFriendlyLocationName($customerFriendlyLocationName);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param $createdAt
     * @return mixed
     */
    public function setCreatedAt($createdAt);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param $updatedAt
     * @return mixed
     */
    public function setUpdatedAt($updatedAt);


}