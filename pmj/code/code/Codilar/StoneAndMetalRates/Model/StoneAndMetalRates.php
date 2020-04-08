<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 7/11/18
 * Time: 6:46 PM
 */
namespace Codilar\StoneAndMetalRates\Model;
use Magento\Framework\Model\AbstractModel;
use Codilar\StoneAndMetalRates\Api\Data\StoneAndMetalRatesInterface;
use phpDocumentor\Reflection\Types\Self_;

class StoneAndMetalRates extends \Magento\Framework\Model\AbstractModel implements StoneAndMetalRatesInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'stone_metal_rates';

    /**
     * @var string
     */
    protected $_cacheTag = 'stone_metal_rates';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'stone_metal_rates';
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
        $this->_init('Codilar\StoneAndMetalRates\Model\ResourceModel\StoneAndMetalRates');
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
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * @param int $id
     * @return StoneAndMetalRatesInterface
     */
    public function setStoreId($id)
    {
        return $this->setData(self::STORE_ID, $id);
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
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @param $type
     * @return mixed
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE,$type);
    }

    /**
     * Get Name.
     *
     * @return varchar
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
        return $this->setData(self::NAME, $name);
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->getData(self::RATE);
    }

    /**
     * @param $rate
     * @return mixed
     */
    public function setRate($rate)
    {
        return $this->setData(self::RATE,$rate);
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->getData(self::UNIT);
    }

    /**
     * @param $unit
     * @return mixed|void
     */
    public function setUnit($unit)
    {
        $this->setData(self::UNIT,$unit);
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
     * @param $name
     * @param $type
     * @param array $storeId
     * @return bool|mixed
     */
    public function getRateByName($name,$type,$storeId=array(0,1))
    {

       $rates= $this->getCollection()->addFieldToFilter('name',$name)->addFieldToFilter('type',$type)->addFieldToFilter(
           'store_id',
           [['fin' => $storeId], ['null' => true]]
       );
        if(count($rates)){
            return $rates->getFirstItem()->getRate();
        }
        return false;
    }

}


