<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 22/7/19
 * Time: 4:41 PM
 */

namespace Codilar\RelatedProducts\Helper;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\App\ResourceConnectionFactory;

class Data
{
    private $resourceConnectionFactory;
    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * @param ResourceConnectionFactory $resourceConnectionFactory
     */
    public function __construct(
        ResourceConnectionFactory $resourceConnectionFactory,
                                Attribute $attribute
    ) {
        $this->resourceConnectionFactory = $resourceConnectionFactory;
        $this->attribute = $attribute;
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
     * @param $id
     * @return \Magento\Framework\DB\Select
     */
    public function getProductCategoryId($id)
    {
        $connection = $this->getNewResourceConnection();
        $sql = $connection->select()
            ->from(['P' => 'catalog_category_product'])
            ->joinLeft(
                ['I' => 'catalog_category_entity'],
                "I.entity_id = P.category_id"
            )->joinLeft(
                ['Q' => 'catalog_product_index_price'],
                "Q.entity_id = P.product_id"
            )
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['P.category_id as CategoryId','Q.final_price'])
            ->where($connection->quoteInto('P.product_id = ?', $id))
            ->order('I.level DESC')
            ->limit(1);

        return $sql;
    }

    /**
     * @param $categoryId
     * @param $productPrice
     * @return \Magento\Framework\DB\Select
     */
    public function getRelatedProductIds($categoryId, $productPrice)
    {
        $statusId = $this->getAttributeId('status');
        $connection = $this->getNewResourceConnection();
        $sql = $connection->select()
            ->from(['P' => 'catalog_category_product'])
            ->joinLeft(
                ['I' => 'catalog_product_index_price'],
                "I.entity_id = P.product_id"
            )->joinLeft(
                ['E' => 'catalog_product_entity_int'],
                "E.entity_id = P.entity_id"
            )->joinLeft(
                ['Q' => 'cataloginventory_stock_status'],
                "Q.product_id = P.product_id"
            )
           ->reset(\Zend_Db_Select::COLUMNS)
           ->columns(['P.product_id as ProductId','abs(I.final_price-' . $productPrice . ') as finalPrice'])
            ->where(sprintf('E.attribute_id = %s', $statusId))
            ->where("I.customer_group_id = 1")
            ->where($connection->quoteInto('E.value = ?', Status::STATUS_ENABLED))
            ->where($connection->quoteInto('P.category_id = ?', $categoryId))
            ->group('P.product_id')
            ->order('finalPrice ASC')
            ->limit(10);
        return $sql;
    }

    /**
     * @param $productId
     * @return \Magento\Framework\DB\Select
     */
    public function getNullPrice($productId)
    {
        $price = $this->getAttributeId('price');
        $connection = $this->getNewResourceConnection();
        $sql = $connection->select()
            ->from(['P' => 'catalog_product_entity_decimal'])
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['P.value'])
            ->where(sprintf('P.attribute_id = %s', $price))
            ->where($connection->quoteInto('P.entity_id = ?', $productId))
            ->limit(1);
        return $sql;
    }
}
