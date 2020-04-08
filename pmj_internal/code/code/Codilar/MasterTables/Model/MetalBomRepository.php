<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 1:49 PM
 */

namespace Codilar\MasterTables\Model;

use Codilar\MasterTables\Api\MetalBomRepositoryInterface;
use Codilar\MasterTables\Model\ResourceModel\MetalBom\CollectionFactory as MetalBomCollection;
use Codilar\MasterTables\Model\MetalBom as Model;
use Codilar\MasterTables\Model\ResourceModel\MetalBom as ResourceModel;
use Codilar\MasterTables\Model\MetalBomFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class MetalBomRepository implements MetalBomRepositoryInterface
{
    /**
     * @var ResourceModel
     */
    protected $resourceModel;
    /**
     * @var MetalBomFactory
     */
    protected $modelFactory;
    /**
     * @var MetalBomCollection
     */
    protected  $collectionFactory;

    /**
     * MetalBomRepository constructor.
     * @param ResourceModel $resourceModel
     * @param MetalBomFactory $modelFactory
     * @param MetalBomCollection $collectionFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        MetalBomCollection $collectionFactory
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
     * @param $metalBomId
     * @return mixed
     */
    public function getById($metalBomId)
    {
        return $this->modelFactory->create()->load($metalBomId, 'metal_bom_id');
    }

    /**
     * @param $id
     * @return bool|mixed
     * @throws LocalizedException
     */
    public function deleteById($id)
    {
        $metalBomItem = $this->getById($id);
        try {
            $this->delete($metalBomItem);
        } catch (LocalizedException $e) {
            throw new LocalizedException(__("Error deleting MetalBom with Id : ".$metalBomItem->getId()));
        }
        return true;
    }

    /**
     * @param $metalDomVariant
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|mixed
     */
    public function getIndianRatePerGram($metalDomVariant)
    {
        return $this->getCollection()->addFieldToFilter('metal_bom_variant',$metalDomVariant)->addFieldToSelect('indian_rate_gram')->getFirstItem()->getIndianRateGram();
    }

    /**
     * @param $metalDomVariant
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|mixed
     */
    public function getUsaRatePerGram($metalDomVariant)
    {
        return $this->getCollection()->addFieldToFilter('metal_bom_variant',$metalDomVariant)->addFieldToSelect('usa_rate_gram')->getFirstItem()->getUsaRateGram();
    }
}