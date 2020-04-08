<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 2:50 PM
 */

namespace Codilar\Appointment\Model;

use Codilar\Appointment\Api\Data\AppointmentRequestInterface;

class AppointmentRequest extends \Magento\Framework\Model\AbstractModel implements AppointmentRequestInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_appointment';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_appointment';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_appointment_request_collection';

    protected function _construct()
    {
        $this->_init('Codilar\Appointment\Model\ResourceModel\AppointmentRequest');
    }

    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @param mixed $Id
     * @return AppointmentRequest|\Magento\Framework\Model\AbstractModel
     */
    public function setRequestId($Id)
    {
        return $this->setData(self::ID, $Id);
    }

    /**
     * @return mixed
     */
    public function getRequestProductSku()
    {
        return $this->getData(self::PRODUCT_SKU);
    }

    /**
     * @param $productSku
     * @return AppointmentRequest
     */
    public function setRequestProductSku($productSku)
    {
        return $this->setData(self::PRODUCT_SKU, $productSku);
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
    public function getNote()
    {
        return $this->getData(self::NOTE);
    }

    /**
     * @param $note
     * @return AppointmentRequest
     */
    public function setNote($note)
    {
        return $this->setData(self::NOTE, $note);
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
     * @return AppointmentRequest
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
     * @return AppointmentRequest
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @return mixed
     */
    public function getAppointmentRequestStatus()
    {
        return $this->getData(self::APPOINTMENT_REQUEST_STATUS);
    }

    /**
     * @param $appointmentRequestStatus
     * @return mixed
     */
    public function setAppointmentRequestStatus($appointmentRequestStatus)
    {
        return $this->getData(self::APPOINTMENT_REQUEST_STATUS, $appointmentRequestStatus);

    }

    /**
     * @return mixed
     */
    public function getVideostoreRequestStatus()
    {
        return $this->getData(self::VIDEOSTORE_REQUEST_STATUS);
    }

    /**
     * @param $videostoreRequestStatus
     * @return AppointmentRequest
     */
    public function setVideostoreRequestStatus($videostoreRequestStatus)
    {
        return $this->setData(self::VIDEOSTORE_REQUEST_STATUS, $videostoreRequestStatus);
    }


    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * @param $country
     * @return AppointmentRequest|mixed
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->getData(self::PLACE);
    }

    /**
     * @param $place
     * @return mixed
     */
    public function setPlace($place)
    {
        return $this->setData(self::PLACE, $place);
    }

    /**
     * @return mixed
     */
    public function getRequestUrl()
    {
        return $this->getData(self::REQUEAT_URL);

    }

    /**
     * @param $requestUrl
     * @return mixed
     */
    public function setRequestUrl($requestUrl)
    {
        return $this->setData(self::REQUEAT_URL, $requestUrl);

    }

}