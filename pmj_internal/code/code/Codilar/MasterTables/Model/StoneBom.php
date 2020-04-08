<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 1:12 PM
 */

namespace Codilar\MasterTables\Model;

use Codilar\MasterTables\Api\Data\StoneBomInterface;

class StoneBom extends \Magento\Framework\Model\AbstractModel implements StoneBomInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_stone_bom';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_stone_bom';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_stone_bom_collection';

    protected function _construct()
    {
        $this->_init('Codilar\MasterTables\Model\ResourceModel\StoneBom');
    }

    /**
     * @return mixed
     */
    public function getStoneBomID()
    {
        return $this->getData(self::STONE_BOM_ID);

    }

    /**
     * @param $stoneBomID
     * @return StoneBom|mixed
     */
    public function setStoneBomID($stoneBomID)
    {
        return $this->setData(self::STONE_BOM_ID, $stoneBomID);

    }

    /**
     * @return mixed
     */
    public function getStoneBomVariant()
    {
        return $this->getData(self::STONE_BOM_VARIANT);
    }

    /**
     * @param $stoneBomVariant
     * @return StoneBom|mixed
     */
    public function setStoneBomVariant($stoneBomVariant)
    {
        return $this->setData(self::STONE_BOM_VARIANT, $stoneBomVariant);

    }

    /**
     * @return mixed
     */
    public function getStoneType()
    {
        return $this->getData(self::STONE_TYPE);

    }

    /**
     * @param $stoneType
     * @return mixed
     */
    public function setStoneType($stoneType)
    {
        return $this->setData(self::STONE_TYPE, $stoneType);

    }
    /**
     * @return mixed
     */
    public function getStoneShape()
    {
        return $this->getData(self::STONE_SHAPE);

    }

    /**
     * @param $stoneShape
     * @return StoneBom|mixed
     */
    public function setStoneShape($stoneShape)
    {
        return $this->setData(self::STONE_SHAPE, $stoneShape);

    }

    /**
     * @return mixed
     */
    public function getStoneQuality()
    {
        return $this->getData(self::STONE_QUALITY);

    }

    /**
     * @param $stoneQuality
     * @return StoneBom|mixed
     */
    public function setStoneQuality($stoneQuality)
    {
        return $this->setData(self::STONE_QUALITY, $stoneQuality);

    }

    /**
     * @return mixed
     */
    public function getStoneColor()
    {
        return $this->getData(self::STONE_COLOR);

    }

    /**
     * @param $stoneColor
     * @return StoneBom|mixed
     */
    public function setStoneColor($stoneColor)
    {
        return $this->setData(self::STONE_COLOR, $stoneColor);

    }

    /**
     * @return mixed
     */
    public function getWtRange()
    {
        return $this->getData(self::WT_RANGE);

    }

    /**
     * @param $wtRange
     * @return StoneBom|mixed
     */
    public function setWtRange($wtRange)
    {
        return $this->setData(self::WT_RANGE, $wtRange);

    }

    /**
     * @return mixed
     */
    public function getIndianRateCarat()
    {
        return $this->getData(self::INDIAN_RATE_CARAT);

    }

    /**
     * @param $indianRateCarat
     * @return StoneBom|mixed
     */
    public function setIndianRateCarat($indianRateCarat)
    {
        return $this->setData(self::INDIAN_RATE_CARAT, $indianRateCarat);

    }

    /**
     * @return mixed
     */
    public function getUsaRateCarat()
    {
        return $this->getData(self::USA_RATE_CARAT);

    }

    /**
     * @param $usaRateCarat
     * @return StoneBom|mixed
     */
    public function setUsaRateCarat($usaRateCarat)
    {
        return $this->setData(self::USA_RATE_CARAT, $usaRateCarat);

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
     * @return StoneBom|mixed
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
     * @return StoneBom|mixed
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);

    }
}