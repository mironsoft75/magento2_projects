<?php

namespace Codilar\Rapnet\Model;

use Codilar\Rapnet\Model\UrlRewrite as Model;
use Codilar\Rapnet\Model\ResourceModel\UrlRewrite\Collection;
use Codilar\Rapnet\Model\ResourceModel\UrlRewrite\CollectionFactory;
use Codilar\Rapnet\Api\UrlRewriteInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Codilar\Rapnet\Model\ResourceModel\UrlRewrite as ResourceModel;
use Codilar\Rapnet\Model\UrlRewriteFactory as ModelFactory;

/**
 * Class UrlRewriteRepository
 * @package Codilar\Rapnet\Model
 */
class UrlRewriteRepository implements UrlRewriteInterface
{
    /**
     * @var ModelFactory
     */
    private $modelFactory;
    /**
     * @var ResourceModel
     */
    private $resourceModel;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * OtpRepository constructor.
     * @param ModelFactory $modelFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory
    ) {
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
    }


    /**
     * @param string $value
     * @param string $field
     * @throws NoSuchEntityException
     * @return Model
     */
    public function load($value, $field = self::ID_FIELD_NAME)
    {
        $model = $this->create();
        $this->resourceModel->load($model, $value, $field);
        if (!$model->getUrlRewriteId()) {
            throw new NoSuchEntityException(__("Entity with $field = $value is Not Found"));
        }
        return $model;
    }

    /**
     * @param Model $model
     * @throws LocalizedException
     * @return Model
     */
    public function save(Model $model)
    {
        $this->resourceModel->save($model);
        return $model;
    }

    /**
     * @param Model $model
     * @throws \Exception
     * @return $this
     */
    public function delete(Model $model)
    {
        $this->resourceModel->delete($model);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }

    /**
     * @return Model
     */
    public function create()
    {
        return $this->modelFactory->create();
    }
}
