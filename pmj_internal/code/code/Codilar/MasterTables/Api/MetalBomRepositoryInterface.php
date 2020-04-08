<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 11:50 AM
 */

namespace Codilar\MasterTables\Api;

use Codilar\MasterTables\Model\MetalBom as Model;
use Codilar\MasterTables\Model\ResourceModel\MetalBom\Collection;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

Interface MetalBomRepositoryInterface
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
     * @return Collection
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
     * @param $metalDomVariant
     * @return mixed
     */
    public function getIndianRatePerGram($metalDomVariant);

    /**
     * @param $metalDomVariant
     * @return mixed
     */
    public function getUsaRatePerGram($metalDomVariant);
}