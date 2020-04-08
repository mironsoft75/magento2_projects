<?php

namespace Codilar\CategoryApi\Helper;

use Codilar\Pwa\Model\Config as PwaConfig;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\App\ResourceConnectionFactory;

class CategoryHelper
{
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
    private $eavConfig;
    /**
     * @var PwaConfig
     */
    private $pwaConfig;

    /**
     * CategoryManagement constructor.
     * @param ResourceConnectionFactory $resourceConnectionFactory
     * @param Attribute $attribute
     * @param Config $eavConfig
     * @param PwaConfig $pwaConfig
     */
    public function __construct(
        ResourceConnectionFactory $resourceConnectionFactory,
        Attribute $attribute,
        Config $eavConfig,
        PwaConfig $pwaConfig
    ) {
        $this->resourceConnectionFactory = $resourceConnectionFactory;
        $this->attribute = $attribute;
        $this->eavConfig = $eavConfig;
        $this->pwaConfig = $pwaConfig;
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

    /**
     * @param $code
     * @param string $type
     * @return \Magento\Eav\Model\Entity\Attribute\AbstractAttribute
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAttribute($code, $type = 'catalog_product')
    {
        return $this->eavConfig->getAttribute($type, $code);
    }

    /**
     * @param $id
     * @param $pageSize
     * @param $pageNumber
     * @return \Magento\Framework\DB\Select
     */
    public function getCategoryProducts($id)
    {
        $statusId = $this->getAttributeId('status');
        $connection = $this->getNewResourceConnection();
        $sql = $connection->select()
            ->from(['C' => 'catalog_category_product'])
            ->joinLeft(
                ['P' => 'catalog_product_entity_int'],
                "P.entity_id = C.product_id"
            )
            ->joinLeft(
                ['Q' => 'cataloginventory_stock_status'],
                "Q.product_id = C.product_id"
            )->where('Q.stock_status = 1')
            ->where($connection->quoteInto('C.category_id = ?', $id))
            ->where($connection->quoteInto('P.attribute_id = ?', $statusId))
            ->where($connection->quoteInto('P.value = ?', Status::STATUS_ENABLED));
        return $sql;
    }

    /**
     * @param $id
     * @param $pageSize
     * @param $pageNumber
     * @param $count
     * @return \Magento\Framework\DB\Select
     */
    public function getCategoryFilteredProducts($id)
    {
        $sql = $this->getCategoryProducts($id);
        $sql = $sql->joinLeft(
                ['I' => 'catalog_product_index_eav'],
                "I.entity_id = C.product_id"
                )->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['C.product_id'])
                ->where('I.store_id = 1')
                ->group('C.product_id');
        return $sql;
    }

    /**
     * @param $products
     * @param $minPrice
     * @param $maxPrice
     * @return \Magento\Framework\DB\Select
     */
    public function getCategoryProductsWithPriceFilter($products, $minPrice, $maxPrice)
    {
        if (count($products)) {
            $productsString = implode(",", $products);
        } else {
            $productsString = 'NULL';
        }
        $connection = $this->getNewResourceConnection();
        $sql = $connection->select()
            ->from(['P' => 'catalog_product_index_price'])
            ->where(sprintf("P.entity_id IN (%s)", $productsString))
            ->where("P.customer_group_id = 1")
            ->where("P.final_price BETWEEN " . $minPrice . " AND " . $maxPrice);
        return $sql;
    }

    /**
     * @param $products
     * @return \Magento\Framework\DB\Select
     */
    public function getCategoryFilters($products)
    {
        if (count($products)) {
            $productsString = implode(",", $products);
        } else {
            $productsString = 'NULL';
        }
        $connection = $this->getNewResourceConnection();
        $sql = $connection->select()
            ->from(['I' => 'catalog_product_index_eav'])
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['I.attribute_id', 'I.value', 'COUNT(I.source_id)'])
            ->where(sprintf("I.source_id IN (%s)", $productsString))
            ->where("I.store_id = 1")
            ->order('I.attribute_id  ASC')
            ->group(['I.attribute_id', 'I.value']);
        return $sql;
    }

    /**
     * @param $products
     * @return \Magento\Framework\DB\Select
     */
    public function getMaxPrice($products)
    {
        if (count($products)) {
            $productsString = implode(",", $products);
        } else {
            $productsString = 'NULL';
        }
        $connection = $this->getNewResourceConnection();
        $sql = $connection->select()
            ->from(['P' => 'catalog_product_index_price'])
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['MAX(P.final_price) as max_price', 'MIN(P.final_price) as min_price'])
            ->where(sprintf("P.entity_id IN (%s)", $productsString))
            ->where("P.customer_group_id = 1");
        return $sql;
    }

    /**
     * @param $products
     * @param $pageNumber
     * @param $pageSize
     * @param $sort
     * @param $categoryId
     * @return \Magento\Framework\DB\Select
     */
    public function sortProducts($products, $pageNumber, $pageSize, $sort, $categoryId)
    {
        if (count($products)) {
            $productsString = implode(",", $products);
        } else {
            $productsString = 'NULL';
        }
        $sortArray = explode("-", $sort);
        $connection = $this->getNewResourceConnection();
        if ($sortArray[0] == 'name') {
            $nameAttributeId = $this->getAttributeId("name");
            $sql = $connection->select()
                ->from(['P' => 'catalog_product_entity_varchar'])
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['P.entity_id as products'])
                ->where(sprintf("P.entity_id IN (%s)", $productsString))
                ->where(sprintf("P.attribute_id = %s", $nameAttributeId))
                ->limitPage($pageNumber, $pageSize)
                ->order("P.value " . $sortArray[1]);
        } elseif ($sortArray[0] == 'price') {
            $sql = $connection->select()
                ->from(['P' => 'catalog_product_index_price'])
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['P.entity_id as products'])
                ->where(sprintf("P.entity_id IN (%s)", $productsString))
                ->where("P.customer_group_id = 1")
                ->limitPage($pageNumber, $pageSize)
                ->order("P.final_price " . $sortArray[1]);
        } else {
            $sql = $connection->select()
                ->from(['P' => 'catalog_category_product'])
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['P.product_id as products'])
                ->where(sprintf("P.product_id IN (%s)", $productsString))
                ->where("P.category_id = " . $categoryId)
                ->limitPage($pageNumber, $pageSize)
                ->order('P.position ASC');
        }
        return $sql;
    }

    /**
     * @param $urlKey
     * @return string
     */
    public function getPwaProductLink($urlKey)
    {
        return $this->pwaConfig->getRedirectBaseUrl() . "/" . $urlKey;
    }

    /**
     * @param $value
     * @return string
     */
    public function getOptionsValueLabel($value)
    {
        if ($value == null) {
            return "";
        }
        $connection = $this->getNewResourceConnection();
        $sql = $connection->select()
            ->from(['V' => 'eav_attribute_option_value'])
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['V.value'])
            ->where("V.option_id = " . $value);
        $result = $connection->fetchRow($sql);
        return $result['value'];
    }

    /**
     * @param $categoryId
     * @return \Magento\Framework\DB\Select
     */
    public function getParentCategories($categoryId)
    {
        $connection = $this->getNewResourceConnection();
        $categoryNameAttributeId = $this->getAttributeId('name', 'catalog_category');
        $sql = $connection->select()
            ->from(['C' => 'catalog_category_entity'])
            ->joinLeft(
                ['CA' => 'catalog_category_entity_varchar'],
                "CA.entity_id = C.entity_id"
            )
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['C.entity_id', 'CA.value'])
            ->where("C.parent_id = " . $categoryId)
            ->where("CA.attribute_id = " . $categoryNameAttributeId);
        return $sql;
    }
}
