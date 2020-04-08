<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 24/11/18
 * Time: 9:07 PM
 */

namespace Codilar\Videostore\Api;

use Codilar\Videostore\Api\Data\VideostoreCartInterface;

interface VideostoreCartRepositoryInterface
{

    /**
     * @param VideostoreCartInterface $product
     * @param bool $saveOptions
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(VideostoreCartInterface $product, $saveOptions = false);

    /**
     * Get info about product by product id
     *
     * @param int $productId
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($productId, $editMode = false, $storeId = null, $forceReload = false);

    /**
     * Delete product
     *
     * @param VideostoreCartInterface $product
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(VideostoreCartInterface $product);

    /**
     * @param integer $id
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($id);

    /**
     * @return \Codilar\Videostore\Model\ResourceModel\VideostoreCart\Collection
     */
    public function getCollection();


    /**
     * @return mixed
     */
    public function getProducts();


    /**
     * @return array
     */
    public function getProductIds();


    /**
     * @return mixed
     */
    public function deleteProductsFromCart();
}