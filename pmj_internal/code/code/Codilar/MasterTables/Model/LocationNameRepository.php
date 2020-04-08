<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 1:35 PM
 */

namespace Codilar\MasterTables\Model;

use Codilar\MasterTables\Api\LocationNameRepositoryInterface;
use Codilar\MasterTables\Model\ResourceModel\LocationName\CollectionFactory as LocationNameCollection;
use Codilar\MasterTables\Model\LocationName as Model;
use Codilar\MasterTables\Model\ResourceModel\LocationName as ResourceModel;
use Codilar\MasterTables\Model\LocationNameFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class LocationNameRepository implements LocationNameRepositoryInterface
{
    protected $resourceModel;

    protected $modelFactory;

    protected  $collectionFactory;

    /**
     * LocationNameRepository constructor.
     * @param ResourceModel $resourceModel
     * @param LocationNameFactory $modelFactory
     * @param LocationNameCollection $collectionFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        LocationNameCollection $collectionFactory
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
     * @param $locationId
     * @return mixed
     */
    public function getById($locationId)
    {
        return $this->modelFactory->create()->load($locationId, 'location_id');
    }

    /**
     * @param $id
     * @return bool|mixed
     * @throws LocalizedException
     */
    public function deleteById($id)
    {
        $locationItem = $this->getById($id);
        try {
            $this->delete($locationItem);
        } catch (LocalizedException $e) {
            throw new LocalizedException(__("Error deleting location with Id : ".$locationItem->getId()));
        }
        return true;
    }

    /**
     * @param $locationName
     * @param $dept
     * @return mixed
     */
    public function getCustomerFriendlyLocation($locationName, $dept)
    {
        return $this->getCollection()->addFieldToFilter('location_name', $locationName)->addFieldToFilter('dept', $dept)->getFirstItem()->getCustomerFriendlyLocationName();
    }

    /**
     * @param $locationName
     * @param $dept
     * @return mixed
     */
    public function getShowAsInStock($locationName, $dept)
    {
        return $this->getCollection()->addFieldToFilter('location_name', $locationName)->addFieldToFilter('dept', $dept)->getFirstItem()->getShowAsInstock();

    }


}