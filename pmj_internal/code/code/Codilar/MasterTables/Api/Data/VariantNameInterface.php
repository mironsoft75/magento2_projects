<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 10:03 AM
 */

namespace Codilar\MasterTables\Api\Data;


Interface VariantNameInterface
{
    const VARIANT_ID = "variant_id";
    const VARIANT_NAME = "variant_name";
    const PRODUCT_TYPE = "product_type";
    const CATEGORY = "category";
    const SUB_CATEGORY = "sub_category";
    const SUB_SUB_CATEGORY = "sub_sub_category";
    const GOLD_DIAMOND_UNCUT = "gold_diamond_uncut";
    const DISPLAY_IN_UI = "display_in_ui";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";


    /**
     * @return mixed
     */
    public function getVariantId();

    /**
     * @param $variantId
     * @return mixed
     */
    public function setVariantId($variantId);

    /**
     * @return mixed
     */
    public function getVariantName();

    /**
     * @param $variantName
     * @return mixed
     */
    public function setVariantName($variantName);


    /**
     * @return mixed
     */
    public function getProductType();

    /**
     * @param $productType
     * @return mixed
     */
    public function setProductType($productType);

    /**
     * @return mixed
     */
    public function getCategory();

    /**
     * @param $category
     * @return mixed
     */
    public function setCategory($category);

    /**
     * @return mixed
     */
    public function getSubCategory();

    /**
     * @param $subCategory
     * @return mixed
     */
    public function setSubCategory($subCategory);

    /**
     * @return mixed
     */
    public function getSubSubCategory();

    /**
     * @param $subSubCategory
     * @return mixed
     */
    public function setSubSubCategory($subSubCategory);

    /**
     * @return mixed
     */
    public function getGoldDiamondUncut();

    /**
     * @param $goldDiamondUncut
     * @return mixed
     */
    public function setGoldDiamondUncut($goldDiamondUncut);

    /**
     * @return mixed
     */
    public function getDisplayInUi();

    /**
     * @param $displayInUi
     * @return mixed
     */
    public function setDisplayInUi($displayInUi);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param $createdAt
     * @return mixed
     */
    public function setCreatedAt($createdAt);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param $updatedAt
     * @return mixed
     */
    public function setUpdatedAt($updatedAt);


}