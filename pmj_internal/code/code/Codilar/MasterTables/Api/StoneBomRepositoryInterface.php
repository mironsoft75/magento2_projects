<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 12:08 PM
 */

namespace Codilar\MasterTables\Api;

use Codilar\MasterTables\Model\StoneBom as Model;
use Codilar\MasterTables\Model\ResourceModel\StoneBom\Collection;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface StoneBomRepositoryInterface
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
     * @param $metalDomId
     * @return mixed
     */
    public function getById($metalDomId);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @param $stoneDomVariant
     * @return mixed
     */
    public function getIndianRatePerCarat($stoneDomVariant);
    /**
     * @param $stoneDomVariant
     * @return mixed
     */
    public function getUsaRatePerCarat($stoneDomVariant);
}