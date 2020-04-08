<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 2:37 PM
 */

namespace Codilar\MasterTables\Model;

use Codilar\MasterTables\Api\StoneBomRepositoryInterface;
use Codilar\MasterTables\Model\ResourceModel\StoneBom\CollectionFactory as StoneBomCollection;
use Codilar\MasterTables\Model\StoneBom as Model;
use Codilar\MasterTables\Model\ResourceModel\StoneBom as ResourceModel;
use Codilar\MasterTables\Model\StoneBomFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class StoneBomRepository implements StoneBomRepositoryInterface
{
    protected $resourceModel;

    protected $modelFactory;

    protected  $collectionFactory;

    /**
     * StoneBomRepository constructor.
     * @param ResourceModel $resourceModel
     * @param StoneBomFactory $modelFactory
     * @param StoneBomCollection $collectionFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        StoneBomCollection $collectionFactory
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
            throw new LocalizedException(__("Error deleting Product with Id : ".$locationItem->getId()));
        }
        return true;
    }

    /**
     * @param $stoneDomVariant
     * @return mixed
     */
    public function getIndianRatePerCarat($stoneDomVariant)
    {
        return $this->getCollection()->addFieldToFilter('stone_bom_variant',$stoneDomVariant)->addFieldToSelect('indian_rate_carat')->getFirstItem()->getIndianRateCarat();
    }
    /**
     * @param $stoneDomVariant
     * @return mixed
     */
    public function getUsaRatePerCarat($stoneDomVariant)
    {
        return $this->getCollection()->addFieldToFilter('stone_bom_variant',$stoneDomVariant)->addFieldToSelect('usa_rate_carat')->getFirstItem()->getUsaRateCarat();

    }
}