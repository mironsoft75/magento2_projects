<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 1:04 PM
 */

namespace Codilar\MasterTables\Model;

use Codilar\MasterTables\Api\Data\MetalInterface;

class Metal extends \Magento\Framework\Model\AbstractModel implements MetalInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_metal';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_metal';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_metal_collection';

    protected function _construct()
    {
        $this->_init('Codilar\MasterTables\Model\ResourceModel\Metal');
    }
    /**
     * @return mixed
     */
    public function getMetalId()
    {
        return $this->getData(self::METAL_ID);

    }

    /**
     * @return mixed
     */
    public function getMetalType()
    {
        return $this->getData(self::METAL_TYPE);

    }

    /**
     * @param $metalType
     * @return Metal|mixed
     */
    public function setMetalType($metalType)
    {
        return $this->setData(self::METAL_TYPE, $metalType);

    }

    /**
     * @return mixed
     */
    public function getKarat()
    {
        return $this->getData(self::KARAT);

    }

    /**
     * @param $karat
     * @return Metal|mixed
     */
    public function setKarat($karat)
    {
        return $this->setData(self::KARAT, $karat);

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
     * @return Metal|mixed
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
     * @return Metal|mixed
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);

    }

    /**
     * @return mixed
     */
    public function getMetalColor()
    {
        return $this->getData(self::METAL_COLOR);

    }

    /**
     * @param $metalColor
     * @return mixed
     */
    public function setMetalColor($metalColor)
    {
        return $this->setData(self::METAL_COLOR, $metalColor);

    }

    /**
     * @return mixed
     */
    public function getKaratColor()
    {
        return $this->getData(self::KARAT_COLOR);

    }

    /**
     * @param $karatColor
     * @return mixed
     */
    public function setKaratColor($karatColor)
    {
        return $this->setData(self::KARAT_COLOR, $karatColor);

    }

    /**
     * @param $metalId
     * @return mixed
     */
    public function setMetalId($metalId)
    {
        return $this->setData(self::METAL_ID, $metalId);

    }
}