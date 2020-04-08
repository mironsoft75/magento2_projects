<?php
namespace Codilar\Appointment\Model;


use Codilar\Appointment\Api\AppointmentRequestRepositoryInterface;
use Codilar\Appointment\Model\ResourceModel\AppointmentRequest\CollectionFactory as AppointmentRequestCollection;
use Codilar\Appointment\Model\AppointmentRequest as Model;
use Codilar\Appointment\Model\ResourceModel\AppointmentRequest as ResourceModel;
use Codilar\Appointment\Model\AppointmentRequestFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class AppointmentRequestRepository implements AppointmentRequestRepositoryInterface
{
    protected $resourceModel;

    protected $modelFactory;

    protected  $collectionFactory;

    /**
     * AppointmentRequestRepository constructor.
     * @param ResourceModel $resourceModel
     * @param AppointmentRequestFactory $modelFactory
     * @param AppointmentRequestCollection $collectionFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        AppointmentRequestCollection $collectionFactory
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
     * @param $productSku
     * @return mixed
     */
    public function getRequestProductSkus($productSku)
    {
        $sku = $this->getCollection()->addFieldToFilter('product_sku', $productSku)->addFieldToSelect('product_sku')->getFirstItem()->getProductSku();
        return $sku;
    }
}