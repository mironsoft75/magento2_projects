<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 10:54 AM
 */

namespace Codilar\Videostore\Api\Data;


interface VideostoreRequestInterface
{

    const VIDEOSTORE_REQUEST_ID = 'videostore_request_id';
    const VIDEOSTORE_PRODUCT_IDS = 'videostore_product_ids';
    const VIDEOSTORE_CUSTOMER_ID = 'videostore_customer_id';
    const FULL_NAME = 'full_name';
    const EMAIL = 'email';
    const MOBILE_NUMBER = 'mobile_number';
    const REQUESTED_DATE = 'requested_date';
    const REQUESTED_TIME = 'requested_time';
    const MESSAGE = "message";
    const VIDEOSTORE_REQUEST_CREATED_AT = "videostore_request_created_at";
    const VIDEOSTORE_REQUEST_UPDATED_AT = "videostore_request_updated_at";
    const VIDEOSTORE_REQUEST_STATUS = "videostore_request_status";
    const ASSIGNED_TO = "assigned_to";
    const COUNTRY = "country";
    const STATE = "state";
    const PENDING_FLAG = "pending_flag";
    const PROCESSING_FLAG = "processing_flag";
    const ACCEPT_FLAG = "accept_flag";
    const REJECT_FLAG = "reject_flag";


    /**
     * @return mixed
     */
    public function getVideostoreRequestId();

    /**
     * @param mixed $videostoreRequestId
     */
    public function setVideostoreRequestId($videostoreRequestId);

    /**
     * @return mixed
     */
    public function getVideostoreProductIds();

    /**
     * @param mixed $videostoreProductIds
     */
    public function setVideostoreProductIds($videostoreProductIds);

    /**
     * @return mixed
     */
    public function getVideostoreCustomerId();

    /**
     * @param mixed $videostoreCustomerId
     */
    public function setVideostoreCustomerId($videostoreCustomerId);

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
    public function getMessage();

    /**
     * @param mixed $message
     */
    public function setMessage($message);

    /**
     * @return mixed
     */
    public function getVideostoreRequestCreatedAt();

    /**
     * @param mixed $videostoreRequestCreatedAt
     */
    public function setVideostoreRequestCreatedAt($videostoreRequestCreatedAt);

    /**
     * @return mixed
     */
    public function getVideostoreRequestUpdatedAt();

    /**
     * @param mixed $videostoreRequestUpdatedAt
     */
    public function setVideostoreRequestUpdatedAt($videostoreRequestUpdatedAt);


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
    public function getAssignedTo();

    /**
     * @param mixed $assignedTo
     */
    public function setAssignedTo($assignedTo);


    /**
     * @return mixed
     */
    public function getPendingFlag();

    /**
     * @param mixed $pendingFlag
     */
    public function setPendingFlag($pendingFlag);

    /**
     * @return mixed
     */
    public function getProcessingFlag();

    /**
     * @param mixed $processingFlag
     */
    public function setProcessingFlag($processingFlag);

    /**
     * @return mixed
     */
    public function getAcceptFlag();

    /**
     * @param mixed $acceptFlag
     */
    public function setAcceptFlag($acceptFlag);

    /**
     * @return mixed
     */
    public function getRejectFlag();

    /**
     * @param mixed $rejectFlag
     */
    public function setRejectFlag($rejectFlag);
}