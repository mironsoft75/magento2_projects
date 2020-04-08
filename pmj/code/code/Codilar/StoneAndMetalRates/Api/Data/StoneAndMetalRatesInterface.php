<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 7/11/18
 * Time: 6:21 PM
 */
namespace Codilar\StoneAndMetalRates\Api\Data;

interface StoneAndMetalRatesInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const STORE_ID='store_id';
    const STATUS='status';
    const TYPE='type';
    const NAME = 'name';
    const RATE='rate';
    const UNIT='unit';
    const CREATED_AT='created_at';
    const UPDATED_AT='updated_at';

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * @param $entityId
     * @return mixed
     */
    public function setEntityId($entityId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param int $id
     * @return $this
     */
    public function setStoreId($id);

    /**
     * @return mixed
     */
    public function getStatus();

    /**
     * @param $status
     * @return mixed
     */
    public function setStatus($status);

    /**
     * @return mixed
     */
    public function getType();

    /**
     * @param $type
     * @return mixed
     */
    public function setType($type);

    /**
     * Get Name.
     *
     * @return varchar
     */
    public function getName();

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getRate();

    /**
     * @param $value
     * @return mixed
     */
    public function setRate($rate);

    /**
     * @return mixed
     */
    public function getUnit();

    /**
     * @param $unit
     * @return mixed
     */
    public function setUnit($unit);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param $creationTime
     * @return mixed
     */
    public function setCreatedAt($creationTime);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param $updatedTime
     * @return mixed
     */
    public function setUpdatedAt($updatedTime);

    /**
     * @param $name
     * @param $data
     * @param $storeId
     * @return mixed
     */
    public function getRateByName($name,$data,$storeId);
}

