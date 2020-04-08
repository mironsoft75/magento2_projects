<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 11:56 AM
 */

namespace Codilar\MasterTables\Api;

use Codilar\MasterTables\Model\Metal as Model;
use Codilar\MasterTables\Model\ResourceModel\Metal\Collection;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

Interface MetalRepositoryInterface
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
     * @param $metalId
     * @return mixed
     */
    public function getById($metalId);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @param $karatColor
     * @return mixed
     */
    public function getByKaratColor($karatColor);

}