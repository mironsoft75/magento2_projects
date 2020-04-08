<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/10/19
 * Time: 3:05 PM
 */

namespace Codilar\AssignImagesAndCategories\Model;

use Codilar\AssignImagesAndCategories\Api\Data\ImagesAndCategoriesInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class ImagesAndCategories
 *
 * @package Codilar\AssignImagesAndCategories\Model
 */
class ImagesAndCategories extends AbstractModel implements ImagesAndCategoriesInterface
{
    /**
     * ImageAndCategory page cache tag.
     */
    const CACHE_TAG = 'codilar_image_and_category';

    /**
     * CacheTag
     *
     * @var string
     */
    protected $_cacheTag = 'codilar_image_and_category';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_image_and_category_collection';

    protected function _construct()
    {
        $this->_init(
            'Codilar\AssignImagesAndCategories\Model\ResourceModel\ImagesAndCategories'
        );
    }
    /**
     * GetId
     *
     * @return string
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);

    }

    /**
     * SetId
     *
     * @param string $id Id
     *
     * @return mixed
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }


    /**
     * GetProductSku
     *
     * @return mixed
     */
    public function getProductSku()
    {
        return $this->getData(self::PRODUCT_SKU);

    }

    /**
     * SetProductSku
     *
     * @param string $productSku ProductSku
     *
     * @return mixed
     */
    public function setProductSku($productSku)
    {
        return $this->setData(self::PRODUCT_SKU, $productSku);

    }

    /**
     * GetImages
     *
     * @return mixed
     */
    public function getImages()
    {
        return $this->getData(self::IMAGES);

    }

    /**
     * SetImages
     *
     * @param string $images Images
     *
     * @return mixed
     */
    public function setImages($images)
    {
        return $this->setData(self::IMAGES, $images);

    }

    /**
     * GetVideos
     *
     * @return mixed
     */
    public function getVideos()
    {
        return $this->getData(self::VIDEOS);

    }

    /**
     * SetVideos
     *
     * @param string $videos Videos
     *
     * @return mixed
     */
    public function setVideos($videos)
    {
        return $this->setData(self::VIDEOS, $videos);

    }

    /**
     * GetVideoThumbnail
     *
     * @return mixed
     */
    public function getVideoThumbnail()
    {
        return $this->getData(self::VIDEO_THUMBNAIL);

    }

    /**
     * SetVideoThumbnail
     *
     * @param string $videoThumbnail VideoThumbnail
     *
     * @return mixed
     */
    public function setVideoThumbnail($videoThumbnail)
    {
        return $this->setData(self::VIDEO_THUMBNAIL, $videoThumbnail);

    }

    /**
     * GetCategories
     *
     * @return mixed
     */
    public function getCategories()
    {
        return $this->getData(self::CATEGORIES);

    }

    /**
     * SetCategories
     *
     * @param string $categories Categories
     *
     * @return mixed
     */
    public function setCategories($categories)
    {
        return $this->getData(self::CATEGORIES, $categories);
    }

    /**
     * GetCustomerFriendlyLocationName
     *
     * @return mixed
     */
    public function getCustomerFriendlyLocationName()
    {
        return $this->getData(self::CUSTOMER_FRIENDLY_LOCATION_NAME);

    }

    /**
     * SetCustomerFriendlyLocationName
     *
     * @param string $locationName LocationName
     *
     * @return mixed
     */
    public function setCustomerFriendlyLocationName($locationName)
    {
        return $this->getData(self::CUSTOMER_FRIENDLY_LOCATION_NAME, $locationName);

    }

    /**
     * GetProductVisibility
     *
     * @return mixed
     */
    public function getProductVisibility()
    {
        return $this->getData(self::PRODUCT_VISIBILITY);

    }

    /**
     * SetProductVisibility
     *
     * @param string $visibility Visibility
     *
     * @return mixed
     */
    public function setProductVisibility($visibility)
    {
        return $this->getData(self::PRODUCT_VISIBILITY, $visibility);

    }
    /**
     * GetCreatedAt
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);

    }

    /**
     * SetCreatedAt
     *
     * @param string $createdAt CreatedAt
     *
     * @return mixed
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);

    }

    /**
     * GetUpdatedAt
     *
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);

    }

    /**
     * SetUpdatedAt
     *
     * @param string $updatedAt UpdatedAt
     *
     * @return mixed
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);

    }
}