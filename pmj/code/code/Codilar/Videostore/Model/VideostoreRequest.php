<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 2:50 PM
 */

namespace Codilar\Videostore\Model;


use Codilar\Videostore\Api\Data\VideostoreRequestInterface;

class VideostoreRequest extends \Magento\Framework\Model\AbstractModel implements VideostoreRequestInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_videostore_request';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_videostore_request';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_videostore_request_collection';

    protected function _construct()
    {
        $this->_init('Codilar\Videostore\Model\ResourceModel\VideostoreRequest');
    }

    /**
     * @return mixed
     */
    public function getVideostoreRequestId()
    {
        return $this->getData(self::VIDEOSTORE_REQUEST_ID);
    }

    /**
     * @param mixed $videostoreRequestId
     * @return mixed
     */
    public function setVideostoreRequestId($videostoreRequestId)
    {
        return $this->setData(self::VIDEOSTORE_REQUEST_ID, $videostoreRequestId);
    }

    /**
     * @return mixed
     */
    public function getVideostoreProductIds()
    {
        return $this->getData(self::VIDEOSTORE_PRODUCT_IDS);
    }

    /**
     * @param mixed $videostoreProductIds
     * @return mixed
     */
    public function setVideostoreProductIds($videostoreProductIds)
    {
        return $this->setData(self::VIDEOSTORE_PRODUCT_IDS, $videostoreProductIds);
    }

    /**
     * @return mixed
     */
    public function getVideostoreCustomerId()
    {
        return $this->getData(self::VIDEOSTORE_CUSTOMER_ID);
    }

    /**
     * @param mixed $videostoreCustomerId
     * @return mixed
     */
    public function setVideostoreCustomerId($videostoreCustomerId)
    {
        return $this->setData(self::VIDEOSTORE_CUSTOMER_ID, $videostoreCustomerId);
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->getData(self::FULL_NAME);
    }

    /**
     * @param mixed $fullName
     * @return mixed
     */
    public function setFullName($fullName)
    {
        return $this->setData(self::FULL_NAME, $fullName);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @param mixed $email
     * @return mixed
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @return mixed
     */
    public function getMobileNumber()
    {
        return $this->getData(self::MOBILE_NUMBER);
    }

    /**
     * @param mixed $mobileNumber
     * @return mixed
     */
    public function setMobileNumber($mobileNumber)
    {
        return $this->setData(self::MOBILE_NUMBER, $mobileNumber);
    }

    /**
     * @return mixed
     */
    public function getRequestedDate()
    {
        return $this->getData(self::REQUESTED_DATE);
    }

    /**
     * @param mixed $requestedDate
     * @return mixed
     */
    public function setRequestedDate($requestedDate)
    {
        return $this->setData(self::REQUESTED_DATE, $requestedDate);
    }

    /**
     * @return mixed
     */
    public function getRequestedTime()
    {
        return $this->getData(self::REQUESTED_TIME);
    }

    /**
     * @param mixed $requestedTime
     * @return mixed
     */
    public function setRequestedTime($requestedTime)
    {
        return $this->setData(self::REQUESTED_TIME, $requestedTime);
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * @param mixed $message
     * @return mixed
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * @return mixed
     */
    public function getVideostoreRequestCreatedAt()
    {
        return $this->getData(self::VIDEOSTORE_REQUEST_CREATED_AT);
    }

    /**
     * @param mixed $videostoreRequestCreatedAt
     * @return mixed
     */
    public function setVideostoreRequestCreatedAt($videostoreRequestCreatedAt)
    {
        return $this->setData(self::VIDEOSTORE_REQUEST_CREATED_AT, $videostoreRequestCreatedAt);
    }

    /**
     * @return mixed
     */
    public function getVideostoreRequestUpdatedAt()
    {
        return $this->getData(self::VIDEOSTORE_REQUEST_UPDATED_AT);
    }

    /**
     * @param mixed $videostoreRequestUpdatedAt
     * @return mixed
     */
    public function setVideostoreRequestUpdatedAt($videostoreRequestUpdatedAt)
    {
        return $this->setData(self::VIDEOSTORE_REQUEST_UPDATED_AT, $videostoreRequestUpdatedAt);
    }

    /**
     * @return mixed
     */
    public function getVideostoreRequestStatus()
    {
        return $this->getData(self::VIDEOSTORE_REQUEST_STATUS);
    }

    /**
     * @param mixed $videostoreRequestStatus
     * @return mixed
     */
    public function setVideostoreRequestStatus($videostoreRequestStatus)
    {
        return $this->setData(self::VIDEOSTORE_REQUEST_STATUS, $videostoreRequestStatus);
    }

    /**
     * @return mixed
     */
    public function getAssignedTo()
    {
        return $this->getData(self::ASSIGNED_TO);
    }

    /**
     * @param mixed $assignedTo
     * @return mixed
     */
    public function setAssignedTo($assignedTo)
    {
        return $this->setData(self::ASSIGNED_TO, $assignedTo);
    }

    /**
     * @return mixed
     */
    public function getPendingFlag()
    {
        return $this->getData(self::PENDING_FLAG);
    }

    /**
     * @param mixed $pendingFlag
     * @return mixed
     */
    public function setPendingFlag($pendingFlag)
    {
        return $this->setData(self::PENDING_FLAG, $pendingFlag);
    }

    /**
     * @return mixed
     */
    public function getProcessingFlag()
    {
        return $this->getData(self::PROCESSING_FLAG);
    }

    /**
     * @param mixed $processingFlag
     * @return mixed
     */
    public function setProcessingFlag($processingFlag)
    {
        return $this->setData(self::PROCESSING_FLAG, $processingFlag);
    }

    /**
     * @return mixed
     */
    public function getAcceptFlag()
    {
        return $this->getData(self::ACCEPT_FLAG);
    }

    /**
     * @param mixed $acceptFlag
     * @return mixed
     */
    public function setAcceptFlag($acceptFlag)
    {
        return $this->setData(self::ACCEPT_FLAG, $acceptFlag);
    }

    /**
     * @return mixed
     */
    public function getRejectFlag()
    {
        return $this->getData(self::REJECT_FLAG);
    }

    /**
     * @param mixed $rejectFlag
     * @return mixed
     */
    public function setRejectFlag($rejectFlag)
    {
        return $this->setData(self::REJECT_FLAG, $rejectFlag);
    }


}