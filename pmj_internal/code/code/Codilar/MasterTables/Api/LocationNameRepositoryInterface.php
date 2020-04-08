<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 11:31 AM
 */

namespace Codilar\MasterTables\Api;

use Codilar\MasterTables\Model\LocationName as Model;
use Codilar\MasterTables\Model\ResourceModel\LocationName\Collection;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

Interface LocationNameRepositoryInterface
{
    /**
     * @param $value
     * @param null $field
     * @return mixed
     */
    public function load($value, $field = null);

    /**
     * @return mixed
     */
    public function create();

    /**
     * @param Model $model
     * @return mixed
     */
    public function save(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function delete(Model $model);

    /**
     * @return mixed
     */
    public function getCollection();

    /**
     * @param $locationId
     * @return mixed
     */
    public function getById($locationId);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @param $locationName
     * @param $dept
     * @return mixed
     */
    public function getCustomerFriendlyLocation($locationName, $dept);

    /**
     * @param $locationName
     * @param $dept
     * @return mixed
     */
    public function getShowAsInStock($locationName, $dept);

}