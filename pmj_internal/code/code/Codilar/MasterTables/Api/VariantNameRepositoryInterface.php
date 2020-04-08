<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 12:14 PM
 */

namespace Codilar\MasterTables\Api;

use Codilar\MasterTables\Model\VariantName as Model;
use Codilar\MasterTables\Model\ResourceModel\VariantName\Collection;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

Interface VariantNameRepositoryInterface
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
     * @param $variantId
     * @return mixed
     */
    public function getById($variantId);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @param $variantName
     * @return mixed
     */
    public function getVariantName($variantName);
}