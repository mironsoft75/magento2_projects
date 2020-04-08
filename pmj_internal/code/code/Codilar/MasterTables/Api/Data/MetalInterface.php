<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 10:11 AM
 */

namespace Codilar\MasterTables\Api\Data;


Interface MetalInterface
{
    const METAL_ID = "metal_id";
    const KARAT_COLOR = "karat_color";
    const METAL_TYPE = "metal_type";
    const KARAT = "karat";
    const METAL_COLOR = "metal_color";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";


    /**
     * @return mixed
     */
    public function getMetalId();

    /**
     * @return mixed
     */
    public function getMetalType();

    /**
     * @param $metalType
     * @return mixed
     */
    public function setMetalType($metalType);

    /**
     * @return mixed
     */
    public function getKarat();

    /**
     * @param $karat
     * @return mixed
     */
    public function setKarat($karat);

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
    public function getMetalColor();

    /**
     * @param $metalColor
     * @return mixed
     */
    public function setMetalColor($metalColor);

    /**
     * @return mixed
     */
    public function getKaratColor();

    /**
     * @param $karatColor
     * @return mixed
     */
    public function setKaratColor($karatColor);

    /**
     * @param $metalId
     * @return mixed
     */
    public function setMetalId($metalId);
}