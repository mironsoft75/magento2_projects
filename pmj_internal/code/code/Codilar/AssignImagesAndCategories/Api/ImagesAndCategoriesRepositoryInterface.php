<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/10/19
 * Time: 2:39 PM
 */

namespace Codilar\AssignImagesAndCategories\Api;

use Codilar\AssignImagesAndCategories\Model\ImagesAndCategories as Model;


/**
 * Interface ImagesAndCategoriesRepositoryInterface
 *
 * @package Codilar\AssignImagesAndCategories\Api
 */
Interface ImagesAndCategoriesRepositoryInterface
{
    /**
     * Load
     *
     * @param string $value Value
     * @param string $field Field
     *
     * @return mixed
     */
    public function load($value, $field = null);

    /**
     * Create
     *
     * @return mixed
     */
    public function create();

    /**
     * Save
     *
     * @param Model $model Model
     *
     * @return mixed
     */
    public function save(Model $model);

    /**
     * Delete
     *
     * @param Model $model Model
     *
     * @return mixed
     */
    public function delete(Model $model);

    /**
     * GetCollection
     *
     * @return mixed
     */
    public function getCollection();

    /**
     * GetById
     *
     * @param string $id Id
     *
     * @return mixed
     */
    public function getById($id);
    /**
     * GetBySku
     *
     * @param string $sku Sku
     *
     * @return mixed
     */
    public function getBySku($sku);

    /**
     * DeleteById
     *
     * @param string $id Id
     *
     * @return mixed
     */
    public function deleteById($id);
    /**
     * DeleteBySku
     *
     * @param string $sku Sku
     *
     * @return mixed
     */
    public function deleteBySku($sku);

    /**
     * GetImagesBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getImagesBySku($sku);
    /**
     * GetVideoThumbnailBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getVideoThumbnailBySku($sku);

    /**
     * GetVideosBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getVideosBySku($sku);

    /**
     * GetCategoriesBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getCategoriesBySku($sku);

    /**
     * GetLocationNameBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getLocationNameBySku($sku);

    /**
     * GettProductVisibilityBySku
     *
     * @param string $sku sku
     *
     * @return mixed
     */
    public function getProductVisibilityBySku($sku);
}