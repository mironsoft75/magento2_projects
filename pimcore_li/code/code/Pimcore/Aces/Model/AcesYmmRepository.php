<?php

namespace Pimcore\Aces\Model;

use Pimcore\Aces\Model\AcesYmm as Model;
use Pimcore\Aces\Model\ResourceModel\AcesYmm\Collection;
use Pimcore\Aces\Model\ResourceModel\AcesYmm\CollectionFactory;
use Pimcore\Aces\Api\AcesYmmInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Pimcore\Aces\Model\ResourceModel\AcesYmm as ResourceModel;
use Pimcore\Aces\Model\AcesYmmFactory as ModelFactory;

/**
 * Class AcesYmmRepository
 * @package Pimcore\Aces\Model
 */
class AcesYmmRepository implements AcesYmmInterface
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
     * AcesYmmRepository constructor.
     * @param AcesYmmFactory $modelFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory
    )
    {
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
        if (!$model->getId()) {
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