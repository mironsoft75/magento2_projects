<?php

namespace Codilar\Appointment\Api\Data;


interface AppointmentRequestInterface
{

    const ID = "request_id";
    const PRODUCT_SKU = "product_sku";
    const FULL_NAME = "full_name";
    const EMAIL = "email";
    const COUNTRY = "country";
    const PLACE = "place";
    const MOBILE_NUMBER = "mobile_number";
    const REQUESTED_DATE = "requested_date";
    const REQUESTED_TIME = "requested_time";
    const NOTE = "note";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    const APPOINTMENT_REQUEST_STATUS = "appointment_request_status";
    const VIDEOSTORE_REQUEST_STATUS = "videostore_request_status";
    const REQUEAT_URL = "request_url";

    /**
     * @return mixed
     */
    public function getRequestId();

    /**
     * @param $id
     * @return mixed
     */
    public function setRequestId($id);

    /**
     * @return mixed
     */
    public function getRequestProductSku();

    /**
     * @param $productSku
     * @return mixed
     */
    public function setRequestProductSku($productSku);

    /**
     * @return mixed
     */
    public function getFullName();

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName);

    /**
     * @return mixed
     */
    public function getEmail();

    /**
     * @param mixed $email
     */
    public function setEmail($email);

    /**
     * @return mixed
     */
    public function getCountry();

    /**
     * @param $country
     * @return mixed
     */
    public function setCountry($country);

    /**
     * @return mixed
     */
    public function getPlace();

    /**
     * @param $place
     * @return mixed
     */

    public function setPlace($place);

    /**
     * @return mixed
     */
    public function getMobileNumber();

    /**
     * @param mixed $mobileNumber
     */
    public function setMobileNumber($mobileNumber);

    /**
     * @return mixed
     */
    public function getRequestedDate();

    /**
     * @param mixed $requestedDate
     */
    public function setRequestedDate($requestedDate);

    /**
     * @return mixed
     */
    public function getRequestedTime();

    /**
     * @param mixed $requestedTime
     */
    public function setRequestedTime($requestedTime);

    /**
     * @return mixed
     */
    public function getNote();

    /**
     * @param $note
     * @return mixed
     */
    public function setNote($note);

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

    /**
     * @return mixed
     */
    public function getAppointmentRequestStatus();

    /**
     * @param $appointmentRequestStatus
     * @return mixed
     */
    public function setAppointmentRequestStatus($appointmentRequestStatus);

    /**
     * @return mixed
     */
    public function getVideostoreRequestStatus();

    /**
     * @param mixed $videostoreRequestStatus
     */
    public function setVideostoreRequestStatus($videostoreRequestStatus);

    /**
     * @return mixed
     */
    public function getRequestUrl();

    /**
     * @param $requestUrl
     * @return mixed
     */
    public function setRequestUrl($requestUrl);

}