<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 2:30 PM
 */

namespace Codilar\MasterTables\Model;

use Codilar\MasterTables\Api\MetalRepositoryInterface;
use Codilar\MasterTables\Model\ResourceModel\Metal\CollectionFactory as MetalCollection;
use Codilar\MasterTables\Model\Metal as Model;
use Codilar\MasterTables\Model\ResourceModel\Metal as ResourceModel;
use Codilar\MasterTables\Model\MetalFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class MetalRepository implements MetalRepositoryInterface
{
    protected $resourceModel;

    protected $modelFactory;

    protected $collectionFactory;

    /**
     * MetalRepository constructor.
     * @param ResourceModel $resourceModel
     * @param MetalFactory $modelFactory
     * @param MetalCollection $collectionFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        MetalCollection $collectionFactory
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
        $metalItem = $this->getById($id);
        try {
            $this->delete($metalItem);
        } catch (LocalizedException $e) {
            throw new LocalizedException(__("Error deleting metal with Id : " . $metalItem->getId()));
        }
        return true;
    }

    /**
     * @param $karatColor
     * @return mixed
     */
    public function getByKaratColor($karatColor)
    {
        return $this->getCollection()->addFieldToFilter('karat_color', $karatColor)->getFirstItem();
    }

}