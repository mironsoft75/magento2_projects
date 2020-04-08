<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 29/11/18
 * Time: 11:54 PM
 */
namespace Codilar\CustomiseJewellery\Model;
use Magento\Framework\Model\AbstractModel;
use Codilar\CustomiseJewellery\Api\Data\CustomiseJewelleryInterface;
use phpDocumentor\Reflection\Types\Self_;

class CustomiseJewellery extends \Magento\Framework\Model\AbstractModel implements CustomiseJewelleryInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_customise_jewellery';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_customise_jewellery';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_customise_jewellery';
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Codilar\CustomiseJewellery\Model\ResourceModel\CustomiseJewellery');
    }
    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param $entityId
     * @return mixed
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
       return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param $customerId
     * @return mixed
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID,$customerId);
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->getData(self::SESSION_ID);
    }

    /**
     * @param $sessionId
     * @return mixed
     */
    public function setSessionId($sessionId)
    {
        return $this->setData(self::SESSION_ID,$sessionId);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL,$email);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name)
    {
       return $this->setData(self::NAME,$name);
    }

    /**
     * @return mixed
     */
    public function getRequestType()
    {
        return $this->getData(self::REQUEST_TYPE);
    }

    /**
     * @param $requestType
     * @return mixed
     */
    public function setRequestType($requestType)
    {
       return $this->setData(self::REQUEST_TYPE,$requestType);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
       return $this->getData(self::STATUS);
    }

    /**
     * @param $status
     * @return mixed
     */
    public function setStatus($status)
    {
       return $this->setData(self::STATUS,$status);
    }

    /**
     * @return mixed
     */
    public function getProductSku()
    {
        return $this->getData(self::PRODUCT_SKU);
    }

    /**
     * @param $sku
     * @return mixed
     */
    public function setProductSku($sku)
    {
        return $this->setData(self::PRODUCT_SKU,$sku);
    }

    /**
     * @return mixed
     */
    public function getImagePath()
    {
       return $this->getData(self::IMAGE_PATH);
    }

    /**
     * @param $imagePath
     * @return mixed
     */
    public function setImagePath($imagePath)
    {
        return $this->setData(self::IMAGE_PATH,$imagePath);
    }

    /**
     * @return mixed
     */
    public function getBudget()
    {
        return $this->getData(self::BUDGET);
    }

    /**
     * @param $budget
     * @return mixed
     */
    public function setBudget($budget)
    {
       return $this->setData(self::BUDGET,$budget);
    }

    /**
     * @return mixed
     */
    public function getMobileNumber()
    {
        return $this->getData(self::MOBILE_NUMBER);
    }

    /**
     * @param $number
     * @return mixed
     */
    public function setMobileNumber($number)
    {
        return $this->setData(self::MOBILE_NUMBER,$number);
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
       return $this->getData(self::DETAILS);
    }

    /**
     * @param $details
     * @return mixed
     */
    public function setDetails($details)
    {
        return $this->setData(self::DETAILS,$details);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @param $creationTime
     * @return mixed|void
     */
    public function setCreatedAt($creationTime)
    {
        $this->setData(self::CREATED_AT,$creationTime);
    }

    /**
     * @return mixed|void
     */
    public function getUpdatedAt()
    {
        $this->getData(self::UPDATED_AT);
    }

    /**
     * @param $updatedTime
     * @return mixed|void
     */
    public function setUpdatedAt($updatedTime)
    {
        $this->setData(self::UPDATED_AT,$updatedTime);
    }

    /**
     * @return mixed|void
     */
    public function getEmailSent()
    {
        $this->getData(self::EMAIL_SENT);
    }

    /**
     * @param $emailSent
     * @return mixed|void
     */
    public function setEmailSent($emailSent)
    {
        $this->setData(self::EMAIL_SENT,$emailSent);
    }

    /**
     * @return mixed|void
     */
    public function getSendEmail()
    {
       $this->getData(self::SEND_EMAIL);
    }

    /**
     * @param $sendEmail
     * @return mixed|void
     */
    public function setSendEmail($sendEmail)
    {
       $this->setData(self::SEND_EMAIL,$sendEmail);
    }


}
