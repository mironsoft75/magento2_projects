<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 2:42 PM
 */

namespace Codilar\MasterTables\Model;

use Codilar\MasterTables\Api\VariantNameRepositoryInterface;
use Codilar\MasterTables\Model\ResourceModel\VariantName\CollectionFactory as StoneBomCollection;
use Codilar\MasterTables\Model\VariantName as Model;
use Codilar\MasterTables\Model\ResourceModel\VariantName as ResourceModel;
use Codilar\MasterTables\Model\VariantNameFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class VariantNameRepository implements VariantNameRepositoryInterface
{
    protected $resourceModel;

    protected $modelFactory;

    protected $collectionFactory;

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
     * @param $variantId
     * @return mixed
     */
    public function getById($variantId)
    {
        return $this->modelFactory->create()->load($variantId, 'variant_id');
    }

    /**
     * @param $id
     * @return bool|mixed
     * @throws LocalizedException
     */
    public function deleteById($id)
    {
        $variantItem = $this->getById($id);
        try {
            $this->delete($variantItem);
        } catch (LocalizedException $e) {
            throw new LocalizedException(__("Error deleting Product with Id : " . $variantItem->getId()));
        }
        return true;
    }

    /**
     * @param $variantName
     * @return mixed
     */
    public function getVariantName($variantName)
    {
        return $this->getCollection()->addFieldToFilter('variant_name', $variantName)->getFirstItem();
    }
}