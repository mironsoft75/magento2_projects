<?php
namespace Codilar\Videostore\Model;


use Codilar\Videostore\Api\VideostoreRequestRepositoryInterface;
use Codilar\Videostore\Model\ResourceModel\VideostoreRequest\CollectionFactory as VideostoreRequestCollection;
use Codilar\Videostore\Model\VideostoreRequest as Model;
use Codilar\Videostore\Model\ResourceModel\VideostoreRequest as ResourceModel;
use Codilar\Videostore\Model\VideostoreRequestFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class VideostoreRequestRepository implements VideostoreRequestRepositoryInterface
{
    protected $resourceModel;

    protected $modelFactory;

    protected  $collectionFactory;
    /**
     * VideostoreRequestRepository constructor.
     * @param ResourceModel $resourceModel
     * @param VideostoreRequest $modelFactory
     * @param VideostoreRequestCollection $collectionFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        VideostoreRequestCollection $collectionFactory
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
        $model = $this->modelFactory->create();
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
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }

    /**
     * @param $requestId
     * @return array
     */
    public function getProductIds($requestId)
    {
        $Ids = $this->getCollection()->addFieldToFilter('videostore_request_id', $requestId)->addFieldToSelect('videostore_product_ids')->getFirstItem()->getVideoStoreProductIds();
        return $Ids;
    }
}