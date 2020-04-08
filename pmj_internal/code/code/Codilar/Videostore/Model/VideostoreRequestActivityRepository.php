<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 26/11/18
 * Time: 12:57 PM
 */

namespace Codilar\Videostore\Model;


use Codilar\Videostore\Api\VideostoreRequestActivityRepositoryInterface;

use Codilar\Videostore\Model\ResourceModel\VideostoreRequestActivity\Collection as VideostoreRequestActivityCollection;
use Codilar\Videostore\Api\Data\VideostoreRequestActivityInterface as Model;
use Codilar\Videostore\Model\ResourceModel\VideostoreRequestActivity as ResourceModel;
use Codilar\Videostore\Model\VideostoreRequest as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class VideostoreRequestActivityRepository implements VideostoreRequestActivityRepositoryInterface
{
    protected $resourceModel;

    protected $modelFactory;

    protected  $collectionFactory;
    /**
     * VideostoreRequestActivityRepository constructor.
     * @param ResourceModel $resourceModel
     * @param VideostoreRequest $modelFactory
     * @param VideostoreRequestActivityCollection $collectionFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        VideostoreRequestActivityCollection $collectionFactory
    )
    {
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param string $value
     * @param string $field
     * @return Model
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null)
    {
        $model = $this->create();
        $this->resourceModel->load($model, $value, $field);
        if(!$model->getId()) {
            throw NoSuchEntityException::singleField($field, $value);
        }
        return $model;
    }

    /**
     * @return Model
     */
    public function create()
    {
        return $this->modelFactory->create();
    }

    /**
     * @param Model $model
     * @return Model
     * @throws AlreadyExistsException
     */
    public function save(Model $model)
    {
        $this->resourceModel->save($model);
        return $model;
    }

    /**
     * @param Model $model
     * @return $this
     * @throws LocalizedException
     */
    public function delete(Model $model)
    {
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $exception) {
            throw new LocalizedException(__("Error deleting Model with Id : ".$model->getId()));
        }
        return $this;
    }

    /**
     * @return VideostoreRequestActivityCollection
     */
    public function getCollection()
    {
        return $this->collectionFactory;
    }
}