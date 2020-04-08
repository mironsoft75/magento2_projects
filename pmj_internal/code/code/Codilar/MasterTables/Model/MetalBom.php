<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 12:52 PM
 */

namespace Codilar\MasterTables\Model;

use Codilar\MasterTables\Api\Data\MetalBomInterface;


class MetalBom extends \Magento\Framework\Model\AbstractModel implements MetalBomInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_metal_bom';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_metal_bom';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_metal_bom_collection';

    protected function _construct()
    {
        $this->_init('Codilar\MasterTables\Model\ResourceModel\MetalBom');
    }

    /**
     * @return mixed
     */
    public function getMetalBomVariant()
    {
        return $this->getData(self::METAL_BOM_VARIANT);

    }

    /**
     * @param $metalBomVariant
     * @return MetalBom|mixed
     */
    public function setMetalBomVariant($metalBomVariant)
    {
        return $this->setData(self::METAL_BOM_VARIANT, $metalBomVariant);

    }

    /**
     * @return mixed
     */
    public function getItemCode()
    {
        return $this->getData(self::ITEM_CODE);
    }

    /**
     * @return mixed
     */
    public function getPurity()
    {
        return $this->getData(self::PURITY);

    }

    /**
     * @param $purity
     * @return MetalBom|mixed
     */
    public function setPurity($purity)
    {
        return $this->setData(self::PURITY, $purity);

    }

    /**
     * @param $itemCode
     * @return MetalBom|mixed
     */
    public function setItemCode($itemCode)
    {
        return $this->setData(self::ITEM_CODE, $itemCode);

    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->getData(self::COLOR);

    }

    /**
     * @return mixed
     */
    public function getIndianRateGram()
    {
        return $this->getData(self::INDIAN_RATE_GRAM);

    }

    /**
     * @param $indianRateGram
     * @return MetalBom|mixed
     */
    public function setIndianRateGram($indianRateGram)
    {
        return $this->setData(self::INDIAN_RATE_GRAM, $indianRateGram);

    }

    /**
     * @return mixed
     */
    public function getUsaRateGram()
    {
        return $this->getData(self::USA_RATE_GRAM);

    }

    /**
     * @param $usaRateGram
     * @return MetalBom|mixed
     */
    public function setUsaRateGram($usaRateGram)
    {
        return $this->setData(self::USA_RATE_GRAM, $usaRateGram);

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
     * @return MetalBom|mixed
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
     * @return MetalBom|mixed
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);

    }

    /**
     * @param $color
     * @return MetalBom|mixed
     */
    public function setColor($color)
    {
        return $this->setData(self::COLOR, $color);

    }

    /**
     * @return mixed
     */
    public function getMetalBomId()
    {
        return $this->getData(self::METAL_BOM_ID);

    }

    /**
     * @param $metalBomId
     * @return MetalBom|mixed
     */
    public function setMetalBomId($metalBomId)
    {
        return $this->setData(self::METAL_BOM_ID, $metalBomId);

    }
}