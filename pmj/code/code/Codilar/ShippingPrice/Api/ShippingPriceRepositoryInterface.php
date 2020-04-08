<?php

namespace Codilar\ShippingPrice\Api;

use Codilar\ShippingPrice\Model\ShippingPrice as Model;
use Codilar\ShippingPrice\Model\ResourceModel\ShippingPrice\Collection;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ShippingPriceRepositoryInterface
{
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


    /**
     * @param $zipId
     * @return mixed
     */
    public function getDaysByZipId($zipId);
}