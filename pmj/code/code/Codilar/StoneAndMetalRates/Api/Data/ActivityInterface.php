<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/11/18
 * Time: 10:37 AM
 */

namespace Codilar\StoneAndMetalRates\Api\Data;

interface ActivityInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const USER_ID='user_id';
    const USER_NAME = 'user_name';
    const ACTIVITY='activity';
    const NEW_DATA='new_data';
    const DATA_ID='data_id';
    const OLD_DATA='old_data';
    const CREATED_AT='created_at';

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
    public function getUserId();

    /**
     * @param int $id
     * @return $this
     */
    public function setUserId($id);

    /**
     * Get Name.
     *
     * @return varchar
     */
    public function getUserName();

    /**
     * @param $name
     * @return mixed
     */
    public function setUserName($name);

    /**
     * @return mixed
     */
    public function getDataId();

    /**
     * @param $dataId
     * @return mixed
     */
    public function setDataId($dataId);

    /**
     * @return mixed
     */
    public function getActivity();

    /**
     * @param $activity
     * @return mixed
     */
    public function setActivity($activity);


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
   public function getOldData();

    /**
     * @param $data
     * @return mixed
     */
   public function setOldData($data);

    /**
     * @return mixed
     */
   public function getNewData();

    /**
     * @param $data
     * @return mixed
     */
   public function setNewData($data);
}

