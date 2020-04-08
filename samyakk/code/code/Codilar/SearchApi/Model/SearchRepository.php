<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 18/7/19
 * Time: 7:10 PM
 */

namespace Codilar\SearchApi\Model;

use Codilar\CategoryApi\Api\CategoryPage\DetailsManagementInterface;
use Codilar\SearchApi\Api\Data\ProductSuggestionInterface;
use Codilar\SearchApi\Api\Data\ProductSuggestionInterfaceFactory;
use Codilar\SearchApi\Api\SearchRepositoryInterface;
use Codilar\SearchApi\Helper\Data;
use Magento\Framework\App\Request\Http;

class SearchRepository implements SearchRepositoryInterface
{
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var Http
     */
    private $http;
    /**
     * @var ProductSuggestionInterfaceFactory
     */
    private $productSuggestion;
    /**
     * @var DetailsManagementInterface
     */
    private $detailsManagement;
    /**
     * @var \Codilar\CategoryApi\Api\Data\CategoryPageInterfaceFactory
     */
    private $categoryPageInterfaceFactory;

    /**
     * SearchRepository constructor.
     * @param Data $helper
     * @param Http $http
     * @param ProductSuggestionInterfaceFactory $productSuggestion
     * @param DetailsManagementInterface $detailsManagement
     * @param \Codilar\CategoryApi\Api\Data\CategoryPageInterfaceFactory $categoryPageInterfaceFactory
     */
    public function __construct(
        Data $helper,
        Http $http,
        ProductSuggestionInterfaceFactory $productSuggestion,
        DetailsManagementInterface $detailsManagement,
        \Codilar\CategoryApi\Api\Data\CategoryPageInterfaceFactory $categoryPageInterfaceFactory
    ) {
        $this->helper = $helper;
        $this->http = $http;
        $this->productSuggestion = $productSuggestion;
        $this->detailsManagement = $detailsManagement;
        $this->categoryPageInterfaceFactory = $categoryPageInterfaceFactory;
    }

    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPageInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function searchByProductName()
    {
        list($query, $pageNumber, $pageSize) = $this->getUrlParams();
        /* @var \Codilar\CategoryApi\Api\Data\CategoryPageInterface $products */
        $products = $this->categoryPageInterfaceFactory->create();
        $attributeIds = $this->helper->getAttributesIds();
        $productCount = $this->helper->getSearchProductCount($query, $attributeIds);
        $productIds = array_unique($this->helper
            ->getSearchProductByMatch($query, $attributeIds, $pageNumber, $pageSize));
        $products->setStatus(true);
        $products->setName($query);
        $products->setFilters(null);
        $products->setPage($pageNumber);
        $products->setPerPage($pageSize);
        $products->setSortOptions(null);
        $products->setResult($productIds);
        $products->setTotal($productCount);
        $products->setDetails($this->detailsManagement->getProductsDetails($productIds));
        return $products;
    }

    /**
     * @return \Codilar\SearchApi\Api\Data\ProductSuggestionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function searchSuggestionByProductName()
    {
        list($query, $pageNumber, $pageSize) = $this->getUrlParams();
        /** @var ProductSuggestionInterface $product */
        $product = $this->productSuggestion->create();
        $attributeIds = $this->helper->getAttributesIds();
        $productCount = $this->helper->getSearchProductCount($query, $attributeIds);
        $productIds = array_unique($this->helper
            ->getSearchProductByMatch($query, $attributeIds, $pageNumber, $pageSize));
        $products = $this->helper->getProductSuggestion($productIds);
        return $product->setProductSuggestion($products)->setTotal($productCount);
    }

    /**
     * @return array
     */
    private function getUrlParams()
    {
        $params = $this->http->getParams();
        $query = isset($params['q']) ? $params['q'] : '';
        $pageNumber = isset($params['p']) ? $params['p'] : 1;
        $pageSize = isset($params['s']) ? $params['s'] : 8;
        return [$query, $pageNumber, $pageSize];
    }

    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPageInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function searchProductBySku()
    {
        $params = $this->http->getParams();
        $skus = isset($params['special']) ? $params['special'] : '';
        $skusArray = explode(',', $skus);
        $query = [];
        foreach ($skusArray as $sku) {
            $query[] = "'" . $sku . "'";
        }
        $query = implode(',', $query);
        $productIds = $this->helper->getSearchProductBySku($query);
        /* @var \Codilar\CategoryApi\Api\Data\CategoryPageInterface $products */
        $products = $this->categoryPageInterfaceFactory->create();
        $products->setStatus(true);
        $products->setName($skus);
        $products->setFilters(null);
        $products->setPage(1);
        $products->setPerPage(100);
        $products->setSortOptions(null);
        $products->setResult($productIds);
        $products->setTotal(0);
        $products->setDetails($this->detailsManagement->getProductsDetails($productIds));
        return $products;
    }
}
