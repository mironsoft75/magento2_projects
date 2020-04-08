<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/4/19
 * Time: 1:36 PM
 */

namespace Codilar\ShippingPrice\Model;

use Codilar\ShippingPrice\Api\ShippingPriceRepositoryInterface;
use Codilar\ShippingPrice\Model\ResourceModel\ShippingPrice\CollectionFactory as ShippingPriceCollection;
use Codilar\ShippingPrice\Model\ShippingPrice as Model;
use Codilar\ShippingPrice\Model\ResourceModel\ShippingPrice as ResourceModel;
use Codilar\ShippingPrice\Model\ShippingPriceFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class ShippingPriceRepository implements ShippingPriceRepositoryInterface
{
    protected $resourceModel;

    protected $modelFactory;

    protected $collectionFactory;

    /**
     * ShippingPriceRepository constructor.
     * @param ResourceModel $resourceModel
     * @param ShippingPriceFactory $modelFactory
     * @param ShippingPriceCollection $collectionFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        ShippingPriceCollection $collectionFactory
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
        if (!$model->getId()) {
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
            throw new LocalizedException(__("Error deleting Model with Id : " . $model->getId()));
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
     * @param $zipId
     * @return mixed
     */
    public function getDaysByZipId($zipId)
    {
        $days = $this->getCollection()->addFieldToFilter('dest_zip', $zipId)->addFieldToSelect('days')->getFirstItem()->getDays();
        return $days;
    }

}