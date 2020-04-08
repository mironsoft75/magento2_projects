<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 18/7/19
 * Time: 7:07 PM
 */

namespace Codilar\SearchApi\Api;


interface SearchRepositoryInterface
{
    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPageInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function searchByProductName();

    /**
     * @return \Codilar\SearchApi\Api\Data\ProductSuggestionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function searchSuggestionByProductName();

    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPageInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function searchProductBySku();
}
