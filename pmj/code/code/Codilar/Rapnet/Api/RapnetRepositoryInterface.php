<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/2/19
 * Time: 1:39 PM
 */

namespace Codilar\Rapnet\Api;

use Codilar\Rapnet\Model\Rapnet as Model;
use Codilar\Rapnet\Model\ResourceModel\Rapnet\Collection;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface RapnetRepositoryInterface
{
    const ID_FIELD_NAME = "id";

    /**
     * @param string $value
     * @param string $field
     * @return Model
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null);

    /**
     * @return Model
     */
    public function create();

    /**
     * @param Model $model
     * @return Model
     * @throws AlreadyExistsException
     */
    public function save(Model $model);

    /**
     * @param Model $model
     * @return $this
     * @throws LocalizedException
     */
    public function delete(Model $model);

    /**
     * @return Collection
     */
    public function getCollection();
}
