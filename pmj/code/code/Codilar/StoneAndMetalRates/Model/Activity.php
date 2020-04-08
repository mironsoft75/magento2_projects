<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/11/18
 * Time: 10:59 AM
 */
namespace Codilar\StoneAndMetalRates\Model;
use Codilar\StoneAndMetalRates\Api\Data\ActivityInterface;
use Magento\Framework\Model\AbstractModel;
use phpDocumentor\Reflection\Types\Self_;

class Activity extends \Magento\Framework\Model\AbstractModel implements ActivityInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'rate_user_activity';

    /**
     * @var string
     */
    protected $_cacheTag = 'rate_user_activity';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'rate_user_activity';

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Codilar\StoneAndMetalRates\Model\ResourceModel\Activity');
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
     * @return int
     */
    public function getUserId()
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * @param int $id
     * @return ActivityInterface
     */
    public function setUserId($id)
    {
        return $this->setData(self::USER_ID, $id);
    }

    /**
     * @return \Codilar\StoneAndMetalRates\Api\Data\varchar
     */
    public function getUserName()
    {
        return $this->getData(self::USER_NAME);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function setUserName($name)
    {
        return $this->setData(self::USER_NAME, $name);
    }

    /**
     * @return mixed
     */
    public function getDataId()
    {
       return $this->getData(self::DATA_ID);
    }

    /**
     * @param $dataId
     * @return mixed
     */
    public function setDataId($dataId)
    {
       return $this->setData(self::DATA_ID,$dataId);
    }

    /**
     * @return mixed
     */

    public function getActivity()
    {
       return $this->getData(self::ACTIVITY);
    }

    /**
     * @param $activity
     * @return mixed
     */
    public function setActivity($activity)
    {
        return $this->setData(self::ACTIVITY,$activity);
    }

    /**
     * @return mixed
     */
    public function getNewData()
    {
       return $this->getData(self::NEW_DATA);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function setNewData($data)
    {
       return $this->setData(self::NEW_DATA,$data);
    }

    /**
     * @return mixed
     */
    public function getOldData()
    {
        return $this->getData(self::OLD_DATA);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function setOldData($data)
    {
        return $this->setData(self::OLD_DATA,$data);
    }

    /**
     * @return mixed|void
     */
    public function getCreatedAt()
    {
        $this->getData(self::CREATED_AT);
    }

    /**
     * @param $creationTime
     * @return mixed|void
     */
    public function setCreatedAt($creationTime)
    {
        $this->setData(self::CREATED_AT, $creationTime);
    }

}