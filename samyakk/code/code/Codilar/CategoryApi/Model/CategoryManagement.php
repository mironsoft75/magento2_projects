<?php

namespace Codilar\CategoryApi\Model;

use Codilar\CategoryApi\Api\CategoryManagementInterface;
use Codilar\CategoryApi\Api\CategoryPage\DetailsManagementInterface;
use Codilar\CategoryApi\Api\CategoryPage\SortOptionsManagementInterface;
use Codilar\CategoryApi\Api\Data\CategoryPage\Filters\ItemsInterface;
use Codilar\CategoryApi\Api\Data\CategoryPage\Filters\ItemsInterfaceFactory;
use Codilar\CategoryApi\Api\Data\CategoryPage\FiltersInterfaceFactory;
use Codilar\CategoryApi\Api\Data\CategoryPageInterfaceFactory;
use Codilar\CategoryApi\Helper\CategoryHelper;
use Codilar\CategoryApi\Model\Pool\CategoryProductsAttributesPool;
use Codilar\Core\Helper\Product;
use Codilar\Product\Helper\ProductHelper;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Product\ProductList;
use Magento\Catalog\Model\Config;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\App\Request\Http;

class CategoryManagement implements CategoryManagementInterface
{
    /**
     * @var CategoryHelper
     */
    private $categoryHelper;
    /**
     * @var Http
     */
    private $http;
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var CategoryPageInterfaceFactory
     */
    private $categoryPageInterfaceFactory;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var FiltersInterfaceFactory
     */
    private $filtersInterfaceFactory;
    /**
     * @var ItemsInterfaceFactory
     */
    private $itemsInterfaceFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * @var Product
     */
    private $coreProductHelper;
    /**
     * @var CategoryProductsAttributesPool
     */
    private $categoryProductsAttributesPool;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var DetailsManagementInterface
     */
    private $detailsManagementInterface;
    /**
     * @var SortOptionsManagementInterface
     */
    private $sortOptionsManagement;
    /**
     * @var ProductList
     */
    private $productList;


    /**
     * CategoryManagement constructor.
     * @param CategoryHelper $categoryHelper
     * @param Http $http
     * @param Attribute $attribute
     * @param CategoryPageInterfaceFactory $categoryPageInterfaceFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param FiltersInterfaceFactory $filtersInterfaceFactory
     * @param ItemsInterfaceFactory $itemsInterfaceFactory
     * @param ProductRepositoryInterface $productRepository
     * @param ProductHelper $productHelper
     * @param Product $coreProductHelper
     * @param CategoryProductsAttributesPool $categoryProductsAttributesPool
     * @param Config $config
     * @param DetailsManagementInterface $detailsManagementInterface
     * @param SortOptionsManagementInterface $sortOptionsManagement
     * @param ProductList $productList
     */
    public function __construct(
        CategoryHelper $categoryHelper,
        Http $http,
        Attribute $attribute,
        CategoryPageInterfaceFactory $categoryPageInterfaceFactory,
        CategoryRepositoryInterface $categoryRepository,
        FiltersInterfaceFactory $filtersInterfaceFactory,
        ItemsInterfaceFactory $itemsInterfaceFactory,
        ProductRepositoryInterface $productRepository,
        ProductHelper $productHelper,
        Product $coreProductHelper,
        CategoryProductsAttributesPool $categoryProductsAttributesPool,
        Config $config,
        DetailsManagementInterface $detailsManagementInterface,
        SortOptionsManagementInterface $sortOptionsManagement,
        ProductList $productList
    ) {
        $this->categoryHelper = $categoryHelper;
        $this->http = $http;
        $this->attribute = $attribute;
        $this->categoryPageInterfaceFactory = $categoryPageInterfaceFactory;
        $this->categoryRepository = $categoryRepository;
        $this->filtersInterfaceFactory = $filtersInterfaceFactory;
        $this->itemsInterfaceFactory = $itemsInterfaceFactory;
        $this->productRepository = $productRepository;
        $this->productHelper = $productHelper;
        $this->coreProductHelper = $coreProductHelper;
        $this->categoryProductsAttributesPool = $categoryProductsAttributesPool;
        $this->config = $config;
        $this->detailsManagementInterface = $detailsManagementInterface;
        $this->sortOptionsManagement = $sortOptionsManagement;
        $this->productList = $productList;
    }

    /**
     * @param int $id
     * @return \Codilar\CategoryApi\Api\Data\CategoryPageInterface|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCategory($id)
    {
        /** @var \Codilar\CategoryApi\Api\Data\CategoryPageInterface $categoryPage */
        $categoryPage = $this->categoryPageInterfaceFactory->create();
        /** @var \Magento\Catalog\Api\Data\CategoryInterface $category */
        $category = $this->categoryRepository->get($id);
        $connection = $this->categoryHelper->getNewResourceConnection();
        $params = $this->http->getParams();
        $filters = [];
        $minPrice = null;
        $maxPrice = null;
        $sort = isset($params['sort']) ? $params['sort'] : 'trending';
        $pageNumber = isset($params['page']) ? $params['page'] : 1;
        $pageSizeArray = explode(",", $this->productList->getDefaultLimitPerPageValue(ProductList::VIEW_MODE_GRID));
        $pageSize = $pageSizeArray[0];
        $attributes = [];
        $categoryFilterId = null;
        if (isset($params['filter'])) {
            $filter = $params['filter'];
            $filterArray = json_decode(base64_decode($filter), true);
            $attributeCount = 0;
            foreach ($filterArray as $key => $value) {
                if ($key == 'price') {
                    $minPrice = $value['selected_min'];
                    $maxPrice = $value['selected_max'];
                    unset($filterArray[$key]);
                } elseif ($key == "__categories") {
                    $categoryFilterId = $value;
                } else {
                    $attributeCount++;
                    foreach ($value as $item) {
                        $attributeId = $this->categoryHelper->getAttributeId($key);
                        if ($attributeId) {
                            $attributes[] = $attributeId . "_" . $item;
                            $filters[] = sprintf("I.attribute_id = '%s' AND I.value = '%s'", $attributeId, $item);
                        }
                    }
                }
            }
        }

        $categoryIdFilterSql = $this->categoryHelper->getParentCategories($id);
        $categoryIdFilterSqlResult = $connection->fetchAll($categoryIdFilterSql);

        $allProductsSql = $this->categoryHelper->getCategoryProducts($id);
        $allProductsSqlResult = $connection->fetchAll($allProductsSql);
        foreach ($allProductsSqlResult as $value) {
            $categoryAllProducts[] = $value['product_id'];
        }

        $categoryId = isset($categoryFilterId) ? $categoryFilterId : $id;

        $categoryFilterSql = $this->categoryHelper->getCategoryFilteredProducts($categoryId);

        if (count($filters)) {
            $categoryFilterSql->having(sprintf("COUNT(I.attribute_id) = %s", $attributeCount))
                ->where(implode(' OR ', $filters));
        }

        $categoryFilterSqlResult = $connection->fetchAll($categoryFilterSql);
        $productsArray = [];
        foreach ($categoryFilterSqlResult as $value) {
            $productsArray[] = $value['product_id'];
        }
        $categoryFiltersProduct = array_unique($productsArray);

        $categoryMaxPriceSql = $this->categoryHelper->getMaxPrice($categoryFiltersProduct);
        $categoryPrices = $connection->fetchRow($categoryMaxPriceSql);

        if (isset($minPrice) && isset($maxPrice)) {
            $categoryFiltersWithPriceSql = $this->categoryHelper->getCategoryProductsWithPriceFilter($categoryFiltersProduct, $minPrice, $maxPrice);
            $categoryFiltersWithPriceSqlResult = $connection->fetchAll($categoryFiltersWithPriceSql);
            $finalProducts = [];
            foreach ($categoryFiltersWithPriceSqlResult as $item) {
                $finalProducts[] = $item['entity_id'];
            }
        } else {
            $finalProducts = $categoryFiltersProduct;
        }

        $remainingProducts = array_diff($categoryAllProducts, $finalProducts);

        $selectedProductsSql = $this->categoryHelper->sortProducts($finalProducts, $pageNumber, $pageSize, $sort, $categoryId);
        $selectedProductSqlResult = $connection->fetchAll($selectedProductsSql);

        $selectedProduct = [];
        foreach ($selectedProductSqlResult as $item) {
            $selectedProduct[] = $item['products'];
        }

        $categoryFiltersAllProductSql = $this->categoryHelper->getCategoryFilters($categoryAllProducts);
        $categoryFiltersAllProductSqlResults = $connection->fetchAll($categoryFiltersAllProductSql);

        $categoryFiltersSelectedProductSql = $this->categoryHelper->getCategoryFilters($finalProducts);
        $categoryFiltersSelectedProductSqlResults = $connection->fetchAll($categoryFiltersSelectedProductSql);

        $categoryFiltersRemainingProductSql = $this->categoryHelper->getCategoryFilters($remainingProducts);
        $categoryFiltersRemainingProductSqlResults = $connection->fetchAll($categoryFiltersRemainingProductSql);

        $allProductsAttributesArray = [];
        $attribute = null;
        foreach ($categoryFiltersAllProductSqlResults as $item) {
            $allProductsAttributesArray[] = $item['attribute_id'];
        }
        $allProductsAttributesArray = array_unique($allProductsAttributesArray);

        $allAttributesValue = [];
        $allAttributesCount = [];
        foreach ($allProductsAttributesArray as $attribute) {
            $values = [];
            foreach ($categoryFiltersAllProductSqlResults as $item) {
                if ($item['attribute_id'] == $attribute) {
                    $values[] = $item['value'];
                    $a = $item['attribute_id'] . "_" . $item['value'];
                    $allAttributesCount[$a] = $item['COUNT(I.source_id)'];
                }
            }
            $allAttributesValue[$attribute] = $values;
        }

        $attributesArray = [];
        $attribute = null;
        foreach ($categoryFiltersSelectedProductSqlResults as $item) {
            $attributesArray[] = $item['attribute_id'];
        }
        $attributesArray = array_unique($attributesArray);

        $attributesValue = [];
        $attributesCount = [];
        foreach ($attributesArray as $attribute) {
            $values = [];
            foreach ($categoryFiltersSelectedProductSqlResults as $item) {
                if ($item['attribute_id'] == $attribute) {
                    $values[] = $item['value'];
                    $a = $item['attribute_id'] . "_" . $item['value'];
                    $attributesCount[$a] = $item['COUNT(I.source_id)'];
                }
            }
            $attributesValue[$attribute] = $values;
        }

        $filtersInterfaceArray = [];
//        $filtersInterfaceArray[] = $this->getCategoryFilter($categoryIdFilterSqlResult, $categoryFilterId);
        $filtersInterfaceArray[] = $this->getPriceFilter($categoryPrices, $maxPrice, $minPrice);
        foreach ($allAttributesValue as $key => $value) {
            if (count($value) == 1) {
                continue;
            }
            if ($this->categoryHelper->getAttributeId('visibility') == $key) {
                continue;
            }
            $filtersInterface = $this->filtersInterfaceFactory->create();
            /** @var \Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute */
            $attribute = $this->categoryHelper->getAttribute($key);
            $filtersInterface->setId($attribute->getAttributeCode())
                ->setName($attribute->getDefaultFrontendLabel())
                ->setType($attribute->getFrontendInput());
            $itemsArray = [];
            foreach ($value as $id) {
                /** @var ItemsInterface $itemInterface */
                $itemInterface = $this->itemsInterfaceFactory->create();
                $itemInterface->setId($id)
                    ->setName($attribute->getSource()->getOptionText($id));
                if (isset($attributesCount[$key . "_" . $id])) {
                    $count = $attributesCount[$key . "_" . $id];
                } else {
                    $count = $allAttributesCount[$key . "_" . $id];
                }
                $itemInterface->setCount(0);
                if (in_array($key . "_" . $id, $attributes)) {
                    $itemInterface->setSelected(true);
                } else {
                    $itemInterface->setSelected(false);
                }
                $itemsArray[] = $itemInterface;
            }
            $filtersInterface->setItems($itemsArray);
            $filtersInterfaceArray[] = $filtersInterface;
        }

        $categoryPage->setName($category->getName())
            ->setStatus("OK")
            ->setTotal(count($finalProducts))
            ->setPage($pageNumber)
            ->setPerPage($pageSize)
            ->setResult($selectedProduct)
            ->setFilters($filtersInterfaceArray)
            ->setDetails($this->detailsManagementInterface->getProductsDetails($selectedProduct))
            ->setSortOptions($this->sortOptionsManagement->getSortData($sort));
        return $categoryPage;
    }

    /**
     * @param $categoryPrices
     * @param $minPrice
     * @param $maxPrice
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\FiltersInterface
     */
    public function getPriceFilter($categoryPrices, $maxPrice, $minPrice)
    {
        $filtersInterface = $this->filtersInterfaceFactory->create();
        $filtersInterface->setId('price')
            ->setName("Price")
            ->setType("range");
        $priceArray = [];
        $priceArray[] = $this->getFilterItems("max", "Max Price", $categoryPrices['max_price'], false);
        $priceArray[] = $this->getFilterItems("min", "Min Price", $categoryPrices['min_price'], false);
        $priceArray[] = $this->getFilterItems("selected_max", "Selected Max Price", isset($maxPrice) ? $maxPrice : $categoryPrices['max_price'], false);
        $priceArray[] = $this->getFilterItems("selected_min", "Selected Min Price", isset($minPrice) ? $minPrice : $categoryPrices['min_price'], false);
        $filtersInterface->setItems($priceArray);
        return $filtersInterface;
    }

    public function getCategoryFilter($categoryData, $selectedCategoryId)
    {
        $filtersInterface = $this->filtersInterfaceFactory->create();
        $filtersInterface->setId('__categories')
            ->setName("Categories")
            ->setType("select");
        $items = [];
        foreach ($categoryData as $datum) {
            $selected = false;
            if ($datum['entity_id'] == $selectedCategoryId) {
                $selected = true;
            }
            $items[] = $this->getFilterItems($datum['entity_id'], $datum['value'], 0, $selected);
        }
        $filtersInterface->setItems($items);
        return $filtersInterface;
    }

    /**
     * @param $id
     * @param $name
     * @param $count
     * @param $selected
     * @return ItemsInterface
     */
    public function getFilterItems($id, $name, $count, $selected)
    {
        /** @var ItemsInterface $itemInterface */
        $itemInterface = $this->itemsInterfaceFactory->create();
        return $itemInterface->setId($id)
            ->setName($name)
            ->setCount($count)
            ->setSelected($selected);
    }
}
