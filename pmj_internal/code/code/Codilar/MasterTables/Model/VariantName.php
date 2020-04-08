<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 1:24 PM
 */

namespace Codilar\MasterTables\Model;

use Codilar\MasterTables\Api\Data\VariantNameInterface;

class VariantName extends \Magento\Framework\Model\AbstractModel implements VariantNameInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_variant_name';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_variant_name';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_variant_name_collection';

    protected function _construct()
    {
        $this->_init('Codilar\MasterTables\Model\ResourceModel\VariantName');
    }

    /**
     * @return mixed
     */
    public function getVariantId()
    {
        return $this->getData(self::VARIANT_ID);

    }

    /**
     * @param $variantId
     * @return VariantName|mixed
     */
    public function setVariantId($variantId)
    {
        return $this->setData(self::VARIANT_ID, $variantId);

    }

    /**
     * @return mixed
     */
    public function getVariantName()
    {
        return $this->getData(self::VARIANT_NAME);

    }

    /**
     * @param $variantName
     * @return VariantName|mixed
     */
    public function setVariantName($variantName)
    {
        return $this->setData(self::VARIANT_NAME, $variantName);

    }

    /**
     * @return mixed
     */
    public function getProductType()
    {
        return $this->getData(self::PRODUCT_TYPE);

    }

    /**
     * @param $productType
     * @return VariantName|mixed
     */
    public function setProductType($productType)
    {
        return $this->setData(self::PRODUCT_TYPE, $productType);

    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->getData(self::CATEGORY);

    }

    /**
     * @param $category
     * @return VariantName|mixed
     */
    public function setCategory($category)
    {
        return $this->setData(self::CATEGORY, $category);

    }

    /**
     * @return mixed
     */
    public function getSubCategory()
    {
        return $this->getData(self::SUB_CATEGORY);

    }

    /**
     * @param $subCategory
     * @return mixed
     */
    public function setSubCategory($subCategory)
    {
        return $this->setData(self::SUB_CATEGORY, $subCategory);

    }

    /**
     * @return mixed
     */
    public function getSubSubCategory()
    {
        return $this->getData(self::SUB_SUB_CATEGORY);

    }

    /**
     * @param $subSubCategory
     * @return VariantName|mixed
     */
    public function setSubSubCategory($subSubCategory)
    {
        return $this->setData(self::SUB_SUB_CATEGORY, $subSubCategory);

    }

    /**
     * @return mixed
     */
    public function getGoldDiamondUncut()
    {
        return $this->getData(self::GOLD_DIAMOND_UNCUT);

    }

    /**
     * @param $goldDiamondUncut
     * @return mixed
     */
    public function setGoldDiamondUncut($goldDiamondUncut)
    {
        return $this->setData(self::GOLD_DIAMOND_UNCUT, $goldDiamondUncut);

    }

    /**
     * @return mixed
     */
    public function getDisplayInUi()
    {
        return $this->getData(self::DISPLAY_IN_UI);

    }

    /**
     * @param $displayInUi
     * @return mixed
     */
    public function setDisplayInUi($displayInUi)
    {
        return $this->setData(self::DISPLAY_IN_UI, $displayInUi);

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