<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/10/19
 * Time: 2:44 PM
 */

namespace Codilar\AssignImagesAndCategories\Api\Data;

/**
 * Interface ImagesAndCategoriesInterface
 *
 * @package Codilar\AssignImagesAndCategories\Api\Data
 */
Interface ImagesAndCategoriesInterface
{
    const ENTITY_ID = "entity_id";
    const PRODUCT_SKU = "product_sku";
    const IMAGES = "images";
    const VIDEO_THUMBNAIL = "video_thumbnail";
    const VIDEOS = "videos";
    const CATEGORIES = "categories";
    const CUSTOMER_FRIENDLY_LOCATION_NAME = "customer_friendly_location_name";
    const PRODUCT_VISIBILITY = "product_visibility";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";



    /**
     * GetId
     *
     * @return string
     */
    public function getId();

    /**
     * SetId
     *
     * @param string $id Id
     *
     * @return mixed
     */
    public function setId($id);

    /**
     * GetProductSku
     *
     * @return mixed
     */
    public function getProductSku();

    /**
     * SetProductSku
     *
     * @param string $productSku ProductSku
     *
     * @return mixed
     */
    public function setProductSku($productSku);

    /**
     * GetImages
     *
     * @return mixed
     */
    public function getImages();

    /**
     * SetImages
     *
     * @param string $images Images
     *
     * @return mixed
     */
    public function setImages($images);

    /**
     * GetVideoThumbnail
     *
     * @return mixed
     */
    public function getVideoThumbnail();

    /**
     * SetVideoThumbnail
     *
     * @param string $videoThumbnail VideoThumbnail
     *
     * @return mixed
     */
    public function setVideoThumbnail($videoThumbnail);

    /**
     * GetVideos
     *
     * @return mixed
     */
    public function getVideos();

    /**
     * SetVideos
     *
     * @param string $videos Videos
     *
     * @return mixed
     */
    public function setVideos($videos);

    /**
     * GetCategories
     *
     * @return mixed
     */
    public function getCategories();

    /**
     * SetCategories
     *
     * @param string $categories Categories
     *
     * @return mixed
     */
    public function setCategories($categories);

    /**
     * GetCustomerFriendlyLocationName
     *
     * @return mixed
     */
    public function getCustomerFriendlyLocationName();

    /**
     * SetCustomerFriendlyLocationName
     *
     * @param string $locationName LocationName
     *
     * @return mixed
     */
    public function setCustomerFriendlyLocationName($locationName);

    /**
     * GetProductVisibility
     *
     * @return mixed
     */
    public function getProductVisibility();

    /**
     * SetProductVisibility
     *
     * @param string $visibility Visibility
     *
     * @return mixed
     */
    public function setProductVisibility($visibility);

    /**
     * GetCreatedAt
     *
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * SetCreatedAt
     *
     * @param string $createdAt CreatedAt
     *
     * @return mixed
     */
    public function setCreatedAt($createdAt);

    /**
     * GetUpdatedAt
     *
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * SetUpdatedAt
     *
     * @param string $updatedAt UpdatedAt
     *
     * @return mixed
     */
    public function setUpdatedAt($updatedAt);
}