<?php

namespace Pimcore\Aces\Model;


use Pimcore\Aces\Api\AcesProductsRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use \Pimcore\Aces\Api\Data\AcesProductsInterfaceFactory;
use \Pimcore\Aces\Model\ResourceModel\AcesProducts as ResourceModel;
use \Pimcore\Aces\Model\ResourceModel\AcesProducts\CollectionFactory;

class AcesProductsRepository implements AcesProductsRepositoryInterface
{

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ResourceModel
     */
    private $resourceModel;
    /**
     * @var AcesProductsInterfaceFactory
     */
    private $acesProductsInterfaceFactory;


    /**
     * AcesProductsRepository constructor.
     * @param AcesProductsInterfaceFactory $acesProductsInterfaceFactory
     * @param CollectionFactory            $collectionFactory
     * @param ResourceModel                $resourceModel
     */
    public function __construct(
        AcesProductsInterfaceFactory $acesProductsInterfaceFactory,
        CollectionFactory $collectionFactory,
        ResourceModel $resourceModel
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
        $this->acesProductsInterfaceFactory = $acesProductsInterfaceFactory;
    }

    /**
     * @param mixed $value
     * @param null  $field
     * @return \Pimcore\Aces\Api\Data\AcesProductsInterface
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null)
    {
        $model = $this->create();
        $this->resourceModel->load($model, $value, $field);
        if (!$model->getId()) {
            throw NoSuchEntityException::singleField($field, $value);
        }
        return $model;
    }

    /**
     * @return \Pimcore\Aces\Api\Data\AcesProductsInterface
     */
    public function create()
    {
        return $this->acesProductsInterfaceFactory->create();
    }

    /**
     * @param \Pimcore\Aces\Api\Data\AcesProductsInterface $model
     * @return \Pimcore\Aces\Api\Data\AcesProductsInterface
     * @throws \Exception
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(\Pimcore\Aces\Api\Data\AcesProductsInterface $model)
    {
        $this->resourceModel->save($model);
        return $model;
    }

    /**
     * @param \Pimcore\Aces\Api\Data\AcesProductsInterface $model
     * @return $this
     * @throws LocalizedException
     */
    public function delete(\Pimcore\Aces\Api\Data\AcesProductsInterface $model)
    {
        try {
            $this->resourceModel->delete($model);
            return $this;
        } catch (\Exception $e) {
            throw new LocalizedException(__("Error deleting banner"));
        }
    }

    /**
     * @return ResourceModel\Collection
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }
}