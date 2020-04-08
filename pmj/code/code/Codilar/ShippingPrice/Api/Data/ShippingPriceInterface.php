<?php

namespace Codilar\ShippingPrice\Api\Data;


interface ShippingPriceInterface
{

    const ID = "pk";
    const WEBSITE_ID = "website_id";
    const DEST_COUNTRY_ID = "dest_country_id";
    const DEST_REGION_ID = "dest_region_id";
    const DEST_ZIP = "dest_zip";
    const CONDITION_NAME = "condition_name";
    const CONDITION_VALUE = "condition_value";
    const PRICE = "price";
    const DAYS = "days";

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param $id
     * @return mixed
     */
    public function setId($d);

    /**
     * @return mixed
     */
    public function getWebsiteId();

    /**
     * @param $websiteId
     * @return mixed
     */
    public function setWebsiteId($websiteId);

    /**
     * @return mixed
     */
    public function getCountryId();

    /**
     * @param $countryId
     * @return mixed
     */
    public function setCountryId($countryId);

    /**
     * @return mixed
     */
    public function getRegionId();

    /**
     * @param $regionId
     * @return mixed
     */
    public function setRegionId($regionId);

    /**
     * @return mixed
     */
    public function getZipId();

    /**
     * @param $zipId
     * @return mixed
     */
    public function setZipId($zipId);

    /**
     * @return mixed
     */
    public function getConditionName();

    /**
     * @param $conditionName
     * @return mixed
     */

    public function setConditionName($conditionName);

    /**
     * @return mixed
     */
    public function getConditionValue();

    /**
     * @param $conditionValue
     * @return mixed
     */
    public function setConditionValue($conditionValue);

    /**
     * @return mixed
     */
    public function getPrice();

    /**
     * @param $price
     * @return mixed
     */
    public function setPrice($price);

    /**
     * @return mixed
     */
    public function getDays();

    /**
     * @param $days
     * @return mixed
     */
    public function setDays($days);


}