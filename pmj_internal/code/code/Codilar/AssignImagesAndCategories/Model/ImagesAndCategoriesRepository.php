<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/10/19
 * Time: 3:00 PM
 */

namespace Codilar\AssignImagesAndCategories\Model;

use Codilar\AssignImagesAndCategories\Api\ImagesAndCategoriesRepositoryInterface;
use Codilar\AssignImagesAndCategories\Model\ResourceModel\ImagesAndCategories\CollectionFactory as ImagesAndCategoriesCollectionFactory;
use Codilar\AssignImagesAndCategories\Model\ImagesAndCategories as Model;
use Codilar\AssignImagesAndCategories\Model\ResourceModel\ImagesAndCategories as ResourceModel;
use Codilar\AssignImagesAndCategories\Model\ImagesAndCategoriesFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ImagesAndCategoriesRepository
 * @package Codilar\AssignImagesAndCategories\Model
 */
class ImagesAndCategoriesRepository implements ImagesAndCategoriesRepositoryInterface
{
    const PRODUCT_SKU = "product_sku";
    /**
     * ResourceModel
     *
     * @var ResourceModel
     */
    protected $resourceModel;
    /**
     * ImagesAndCategoriesFactory
     *
     * @var ImagesAndCategoriesFactory
     */
    protected $modelFactory;
    /**
     * ImagesAndCategoriesCollection
     *
     * @var ImagesAndCategoriesCollection
     */
    protected $collectionFactory;

    /**
     * ImagesAndCategoriesRepository constructor.
     *
     * @param ResourceModel $resourceModel ResourceModel
     * @param ImagesAndCategoriesFactory $modelFactory ImagesAndCategoriesFactory
     * @param ImagesAndCategoriesCollectionFactory $collectionFactory ImagesAndCategoriesCollectionFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        ImagesAndCategoriesCollectionFactory $collectionFactory
    )
    {
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Load
     *
     * @param string $value Value
     * @param null $field Field
     *
     * @return mixed
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
     * Create
     *
     * @return mixed
     */
    public function create()
    {
        return $this->modelFactory->create();
    }

    /**
     * Save
     *
     * @param ImagesAndCategories $model Model
     *
     * @return ImagesAndCategories|mixed
     * @throws AlreadyExistsException
     */
    public function save(Model $model)
    {
        $this->resourceModel->save($model);
        return $model;
    }

    /**
     * Delete
     *
     * @param ImagesAndCategories $model Model
     *
     * @return $this|mixed
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
     * GetById
     *
     * @param string $id Id
     *
     * @return mixed
     */
    public function getById($id)
    {
        return $this->modelFactory->create()->load($id, 'entity_id');
    }

    /**
     * DeleteBYId
     *
     * @param string $id Id
     *
     * @return bool|mixed
     * @throws LocalizedException
     */
    public function deleteById($id)
    {
        $item = $this->getById($id);
        try {
            $this->delete($item);
        } catch (LocalizedException $e) {
            throw new LocalizedException(__("Error deleting images and categories with Id : " . $item->getId()));
        }
        return true;
    }

    /**
     * GetBySku
     *
     * @param string $sku Sku
     *
     * @return mixed
     */
    public function getBySku($sku)
    {

        return $this->getCollection()->addFieldToFilter(self::PRODUCT_SKU, $sku)
            ->getFirstItem();

    }

    /**
     * DeleteBySku
     *
     * @param string $sku Sku
     *
     * @return bool|mixed
     * @throws LocalizedException
     */
    public function deleteBySku($sku)
    {
        $item = $this->getBySku($sku);
        try {
            $this->delete($item);
        } catch (LocalizedException $e) {
            throw new LocalizedException(__("Error images and categories with Id : " . $sku));
        }
        return true;
    }

    /**
     * GetImagesBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getImagesBySku($sku)
    {
        return $this->getCollection()->addFieldToFilter(self::PRODUCT_SKU, $sku)
            ->getFirstItem()->getImages();

    }

    /**
     * GetVideosBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getVideosBySku($sku)
    {
        return $this->getCollection()->addFieldToFilter(self::PRODUCT_SKU, $sku)
            ->getFirstItem()->getVideos();
    }


    /**
     * GetCategoriesBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getCategoriesBySku($sku)
    {
        return $this->getCollection()->addFieldToFilter(self::PRODUCT_SKU, $sku)
            ->getFirstItem()->getCategories();
    }

    /**
     * GetLocationNameBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getLocationNameBySku($sku)
    {
        return $this->getCollection()->addFieldToFilter(self::PRODUCT_SKU, $sku)
            ->getFirstItem()->getLocationName();
    }

    /**
     * GettProductVisibilityBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getProductVisibilityBySku($sku)
    {
        return $this->getCollection()->addFieldToFilter(self::PRODUCT_SKU, $sku)
            ->getFirstItem()->getProductVisibility();
    }

    /**
     * GetVideoThumbnailBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getVideoThumbnailBySku($sku)
    {
        return $this->getCollection()->addFieldToFilter(self::PRODUCT_SKU, $sku)
            ->getFirstItem()->getVideoThumbnail();
    }
}