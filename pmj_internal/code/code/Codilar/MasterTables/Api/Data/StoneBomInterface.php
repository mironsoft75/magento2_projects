<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 10:24 AM
 */

namespace Codilar\MasterTables\Api\Data;


interface StoneBomInterface
{
    const STONE_BOM_ID = "stone_bom_id";
    const STONE_BOM_VARIANT = "stone_bom_variant";
    const STONE_TYPE = "stone_type";
    const STONE_SHAPE = "stone_shape";
    const STONE_QUALITY = "stone_quality";
    const STONE_COLOR = "stone_color";
    const WT_RANGE = "wt_range";
    const INDIAN_RATE_CARAT = "indian_rate_carat";
    const USA_RATE_CARAT = "usa_rate_carat";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";


    /**
     * @return mixed
     */
    public function getStoneBomID();

    /**
     * @param $stoneBomID
     * @return mixed
     */
    public function setStoneBomID($stoneBomID);

    /**
     * @return mixed
     */
    public function getStoneBomVariant();

    /**
     * @param $stoneBomVariant
     * @return mixed
     */
    public function setStoneBomVariant($stoneBomVariant);

    /**
     * @return mixed
     */
    public function getStoneType();

    /**
     * @param $stoneType
     * @return mixed
     */
    public function setStoneType($stoneType);
    /**
     * @return mixed
     */
    public function getStoneShape();

    /**
     * @param $stoneShape
     * @return mixed
     */
    public function setStoneShape($stoneShape);


    /**
     * @return mixed
     */
    public function getStoneQuality();

    /**
     * @param $stoneQuality
     * @return mixed
     */
    public function setStoneQuality($stoneQuality);

    /**
     * @return mixed
     */
    public function getStoneColor();

    /**
     * @param $stoneColor
     * @return mixed
     */
    public function setStoneColor($stoneColor);

    /**
     * @return mixed
     */
    public function getWtRange();

    /**
     * @param $wtRange
     * @return mixed
     */
    public function setWtRange($wtRange);

    /**
     * @return mixed
     */
    public function getIndianRateCarat();

    /**
     * @param $indianRateCarat
     * @return mixed
     */
    public function setIndianRateCarat($indianRateCarat);

    /**
     * @return mixed
     */
    public function getUsaRateCarat();

    /**
     * @param $usaRateCarat
     * @return mixed
     */
    public function setUsaRateCarat($usaRateCarat);

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