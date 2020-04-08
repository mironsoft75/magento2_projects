<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 10:31 AM
 */

namespace Codilar\MasterTables\Api\Data;


Interface MetalBomInterface
{
    const METAL_BOM_ID = "metal_bom_id";
    const METAL_BOM_VARIANT = "metal_bom_variant";
    const ITEM_CODE = "item_code";
    const PURITY = "purity";
    const COLOR = "color";
    const INDIAN_RATE_GRAM = "indian_rate_gram";
    const USA_RATE_GRAM = "usa_rate_gram";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";


    /**
     * @return mixed
     */
    public function getMetalBomVariant();

    /**
     * @param $metalBomVariant
     * @return mixed
     */
    public function setMetalBomVariant($metalBomVariant);

    /**
     * @return mixed
     */
    public function getItemCode();

    /**
     * @return mixed
     */
    public function getPurity();

    /**
     * @param $purity
     * @return mixed
     */
    public function setPurity($purity);

    /**
     * @param $itemCode
     * @return mixed
     */
    public function setItemCode($itemCode);

    /**
     * @return mixed
     */
    public function getColor();

    /**
     * @return mixed
     */
    public function getIndianRateGram();

    /**
     * @param $indianRateGram
     * @return mixed
     */
    public function setIndianRateGram($indianRateGram);

    /**
     * @return mixed
     */
    public function getUsaRateGram();

    /**
     * @param $usaRateGram
     * @return mixed
     */
    public function setUsaRateGram($usaRateGram);

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

    /**
     * @param $color
     * @return mixed
     */
    public function setColor($color);

    /**
     * @return mixed
     */
    public function getMetalBomId();

    /**
     * @param $metalBomId
     * @return mixed
     */
    public function setMetalBomId($metalBomId);


}