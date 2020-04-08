<?php

namespace Codilar\Rapnet\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Codilar\Rapnet\Model\UrlRewrite as Model;
use Codilar\Rapnet\Model\ResourceModel\UrlRewrite\Collection;

interface UrlRewriteInterface
{
    const ID_FIELD_NAME = "url_rewrite_id";

    /**
     * @param string $value
     * @param string $field
     * @throws NoSuchEntityException
     * @return Model
     */
    public function load($value, $field = self::ID_FIELD_NAME);

    /**
     * @param Model $model
     * @throws LocalizedException
     * @return Model
     */
    public function save(Model $model);

    /**
     * @param Model $model
     * @throws \Exception
     * @return $this
     */
    public function delete(Model $model);

    /**
     * @return Collection
     */
    public function getCollection();

    /**
     * @return Model
     */
    public function create();
}
