<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 19/7/19
 * Time: 11:16 AM
 */

namespace Codilar\SearchApi\Helper;

use Codilar\SearchApi\Api\Data\ProductSearchSuggestionInterface;
use Codilar\SearchApi\Api\Data\ProductSearchSuggestionInterfaceFactory;
use Codilar\SearchApi\Model\Config;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\App\ResourceConnectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class Data
{
    const CATALOG_SEARCH_FULLTEXT = "catalogsearch_fulltext";
    const SCOPE = "_scope";

    /**
     * @var ResourceConnectionFactory
     */
    private $resourceConnectionFactory;
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var Config
     */
    private $searchConfig;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var ProductSearchSuggestionInterfaceFactory
     */
    private $productSearchSuggestionInterfaceFactory;

    /**
     * Data constructor.
     * @param ResourceConnectionFactory $resourceConnectionFactory
     * @param Config $searchConfig
     * @param Attribute $attribute
     * @param StoreManagerInterface $storeManager
     * @param ProductRepositoryInterface $productRepository
     * @param ProductSearchSuggestionInterfaceFactory $productSearchSuggestionInterfaceFactory
     */
    public function __construct(
        ResourceConnectionFactory $resourceConnectionFactory,
        Config $searchConfig,
        Attribute $attribute,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        ProductSearchSuggestionInterfaceFactory $productSearchSuggestionInterfaceFactory
    ) {
        $this->resourceConnectionFactory = $resourceConnectionFactory;
        $this->attribute = $attribute;
        $this->searchConfig = $searchConfig;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->productSearchSuggestionInterfaceFactory = $productSearchSuggestionInterfaceFactory;
    }

    /**
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     */
    public function getNewResourceConnection()
    {
        return $this->resourceConnectionFactory->create()->getConnection();
    }
    /**
     * @param $code
     * @param string $type
     * @return int
     */
    public function getAttributeId($code, $type = 'catalog_product')
    {
        return $this->attribute->getIdByCode($type, $code);
    }

    public function getProductByName($name)
    {
        $statusId = $this->getAttributeId('status');
        $limit =$this->searchConfig->getLimit();
        $sortOrder = $this->searchConfig->getSortOrder();
        $attribute =$this->searchConfig->getSortOrderAttribute();
        $connection = $this->getNewResourceConnection();
        $nameAttributeId = $this->getAttributeId("name");
        $sql = $connection->select()
            ->from(['P' => 'catalog_product_entity_varchar'])
            ->joinLeft(
                ['C' => 'catalog_product_entity'],
                "C.entity_id = P.entity_id"
            )->joinLeft(
                ['E' => 'catalog_product_entity_int'],
                "E.entity_id = P.entity_id"
            )
            ->joinLeft(
                ['Q' => 'cataloginventory_stock_status'],
                "Q.product_id = P.entity_id"
            )->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['P.value as name'])
            ->where('Q.stock_status = 1')
            ->where('P.value LIKE ?', "%$name%")
            ->where(sprintf("P.attribute_id = %s", $nameAttributeId))
            ->where(sprintf('E.attribute_id = %s', $statusId))
            ->where($connection->quoteInto('E.value = ?', Status::STATUS_ENABLED))
            ->order('C.' . $attribute . ' ' . $sortOrder)
            ->limit($limit);
        return $sql;
    }

    /**
     * @param $name
     * @param $pageNumber
     * @param $pageSize
     * @return \Magento\Framework\DB\Select
     */
    public function getSearchProductIds($name, $pageNumber, $pageSize)
    {
        $statusId = $this->getAttributeId('status');
        $connection = $this->getNewResourceConnection();
        $nameAttributeId = $this->getAttributeId("name");
        $sql = $connection->select()
            ->from(['P' => 'catalog_product_entity_varchar'])
            ->joinLeft(
                ['C' => 'catalog_product_entity'],
                "C.entity_id = P.entity_id"
            )->joinLeft(
                ['E' => 'catalog_product_entity_int'],
                "E.entity_id = P.entity_id"
            )
            ->joinLeft(
                ['Q' => 'cataloginventory_stock_status'],
                "Q.product_id = P.entity_id"
            )
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['P.entity_id as product_id'])
            ->where('Q.stock_status = 1')
            ->where('P.value LIKE ?', "%$name%")
            ->where(sprintf("P.attribute_id = %s", $nameAttributeId))
            ->where(sprintf('E.attribute_id = %s', $statusId))
            ->where($connection->quoteInto('E.value = ?', Status::STATUS_ENABLED))
            ->limitPage($pageNumber, $pageSize);
        return $sql;
    }

    /**
     * @param string $query
     * @param string $attributeIds
     * @param int $pageNumber
     * @param int $pageSize
     * @return integer[] array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSearchProductByMatch($query, $attributeIds, $pageNumber, $pageSize)
    {
        $productSearchSQL = $this->getNewResourceConnection()->select()
            ->from(
                self::CATALOG_SEARCH_FULLTEXT
                . self::SCOPE
                . $this->storeManager->getStore()->getId()
            )->where("MATCH(data_index) AGAINST('$query') AND attribute_id IN ($attributeIds)")
            ->limitPage($pageNumber, $pageSize);
        $productSearchResult = $productSearchSQL->query()->fetchAll();
        $productIds = [];
        foreach ($productSearchResult as $productSearch) {
            $productIds[] = $productSearch['entity_id'];
        }
        return $productIds;
    }

    /**
     * @param string $query
     * @return integer[] array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSearchProductBySku($query)
    {
        $attributeId = $this->getSkuAttributesId();
        $productSearchSQL = $this->getNewResourceConnection()->select()
            ->from(
                self::CATALOG_SEARCH_FULLTEXT
                . self::SCOPE
                . $this->storeManager->getStore()->getId()
            )->where("data_index IN ($query) AND attribute_id = $attributeId");
        $productSearchResult = $productSearchSQL->query()->fetchAll();
        $productIds = [];
        foreach ($productSearchResult as $productSearch) {
            $productIds[] = $productSearch['entity_id'];
        }
        return $productIds;
    }

    /**
     * @param string $query
     * @param string $attributeIds
     * @return integer
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSearchProductCount($query, $attributeIds)
    {
        try {
            $productSearchSQL = $this->getNewResourceConnection()->select()
                ->from(
                    self::CATALOG_SEARCH_FULLTEXT
                    . self::SCOPE
                    . $this->storeManager->getStore()->getId()
                )->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['COUNT(*) as count'])
                ->where("MATCH(data_index) AGAINST('$query') AND attribute_id IN ($attributeIds)");
            $data = $productSearchSQL->query()->fetch();
            return $data['count'];
        } catch (\Zend_Db_Statement_Exception $e) {
            return 0;
        }
    }

    /**
     * @param integer[] $productIds
     * @return \Codilar\SearchApi\Api\Data\ProductSearchSuggestionInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductSuggestion($productIds)
    {
        $products = [];
        foreach ($productIds as $productId) {
            $product = $this->productRepository->getById($productId);
            /** @var ProductSearchSuggestionInterface $productSuggestion */
            $productSuggestion = $this->productSearchSuggestionInterfaceFactory->create();
            $products[] = $productSuggestion->setId($product->getId())
                            ->setName($product->getName())
                            ->setSku($product->getSku())
                            ->setUrlKey($product->getData('url_key'));
        }
        return $products;
    }

    /**
     * @return array|string
     */
    public function getAttributesIds()
    {
        $eavAttributeSQL = $this->getNewResourceConnection()->select()
            ->from(['EA' => 'eav_attribute'])
            ->joinInner(['EET' => 'eav_entity_type'], 'EA.entity_type_id = EET.entity_type_id')
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['EA.attribute_id as attribute_id'])
            ->where(' EA.attribute_code IN ("name", "sku") AND EET.entity_type_code = "catalog_product"');
        $attributeIdArray = $eavAttributeSQL->query()->fetchAll();
        $attributeIds = [];
        foreach ($attributeIdArray as $attributeId) {
            $attributeIds[] = $attributeId['attribute_id'];
        }
        $attributeIds = implode(',', $attributeIds);
        return $attributeIds;
    }

    /**
     * @return integer|null
     */
    protected function getSkuAttributesId()
    {
        try {
            $eavAttributeSQL = $this->getNewResourceConnection()->select()
                ->from(['EA' => 'eav_attribute'])
                ->joinInner(['EET' => 'eav_entity_type'], 'EA.entity_type_id = EET.entity_type_id')
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['EA.attribute_id as attribute_id'])
                ->where(' EA.attribute_code = "sku" AND EET.entity_type_code = "catalog_product"');
            $attributeId = $eavAttributeSQL->query()->fetch();
            return $attributeId['attribute_id'];
        } catch (\Zend_Db_Statement_Exception $e) {
        }
        return null;
    }
}
