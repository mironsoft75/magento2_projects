<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/11/18
 * Time: 6:16 PM
 */
namespace Codilar\CustomiseJewellery\Api\Data;

interface CustomiseJewelleryInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const CUSTOMER_ID='customer_id';
    const SESSION_ID='session_id';
    const EMAIL='email';
    const NAME = 'name';
    const REQUEST_TYPE='request_type';
    const STATUS='status';
    const PRODUCT_SKU='product_sku';
    const IMAGE_PATH='image_path';
    const BUDGET='budget';
    const MOBILE_NUMBER='mobile_number';
    const DETAILS='details';
    const CREATED_AT='created_at';
    const UPDATED_AT='updated_at';
    const SEND_EMAIL='send_email';
    const EMAIL_SENT='email_sent';

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * @param $entityId
     * @return mixed
     */
    public function setEntityId($entityId);

    /**
     * @return mixed
     */
    public function getCustomerId();

    /**
     * @param $customerId
     * @return mixed
     */
    public function setCustomerId($customerId);

    /**
     * @return mixed
     */
    public function getSessionId();

    /**
     * @param $sessionId
     * @return mixed
     */
    public function setSessionId($sessionId);

    /**
     * @return mixed
     */
    public function getEmail();

    /**
     * @param $email
     * @return mixed
     */
    public function setEmail($email);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getRequestType();

    /**
     * @param $requestType
     * @return mixed
     */
    public function setRequestType($requestType);

    /**
     * @return mixed
     */
    public function getStatus();

    /**
     * @param $status
     * @return mixed
     */
    public function setStatus($status);

    /**
     * @return mixed
     */
    public function getProductSku();

    /**
     * @param $sku
     * @return mixed
     */
    public function setProductSku($sku);

    /**
     * @return mixed
     */
    public function getImagePath();

    /**
     * @param $imagePath
     * @return mixed
     */
    public function setImagePath($imagePath);

    /**
     * @return mixed
     */
    public function getBudget();

    /**
     * @param $budget
     * @return mixed
     */
    public function setBudget($budget);

    /**
     * @return mixed
     */
    public function getMobileNumber();

    /**
     * @param $number
     * @return mixed
     */
    public function setMobileNumber($number);

    /**
     * @return mixed
     */
    public function getDetails();

    /**
     * @param $details
     * @return mixed
     */
    public function setDetails($details);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param $creationTime
     * @return mixed
     */
    public function setCreatedAt($creationTime);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param $updatedTime
     * @return mixed
     */
    public function setUpdatedAt($updatedTime);

    /**
     * @return mixed
     */
    public function getSendEmail();

    /**
     * @param $sendEmail
     * @return mixed
     */
    public function setSendEmail($sendEmail);

    /**
     * @return mixed
     */
    public function getEmailSent();

    /**
     * @param $emailSent
     * @return mixed
     */
    public function setEmailSent($emailSent);


}
