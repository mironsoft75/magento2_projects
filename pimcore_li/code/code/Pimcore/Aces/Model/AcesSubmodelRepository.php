<?php

namespace Pimcore\Aces\Model;

use Pimcore\Aces\Model\AcesSubmodel as Model;
use Pimcore\Aces\Model\ResourceModel\AcesSubmodel\Collection;
use Pimcore\Aces\Model\ResourceModel\AcesSubmodel\CollectionFactory;
use Pimcore\Aces\Api\AcesSubmodelInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Pimcore\Aces\Model\ResourceModel\AcesSubmodel as ResourceModel;
use Pimcore\Aces\Model\AcesSubmodelFactory as ModelFactory;

/**
 * Class AcesSubmodelRepository
 * @package Pimcore\Aces\Model
 */
class AcesSubmodelRepository implements AcesSubmodelInterface
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
     * AcesSubmodelRepository constructor.
     * @param AcesSubmodelFactory $modelFactory
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