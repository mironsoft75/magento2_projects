<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory;
use Magento\Catalog\Model\Product\Attribute\Repository;

class Category extends AbstractHelper
{

    protected $layer;
    protected $resourceConnection;
    protected $collection;
    protected $attrRepository;
    protected $eavAttribute;

    /**
     * Category constructor.
     * @param Context $context
     * @param ResourceConnection $resourceConnection
     * @param CollectionFactory $collection
     * @param Repository $repository
     */
    public function __construct(
        Context $context,
        ResourceConnection $resourceConnection,
        CollectionFactory $collection,
        Repository $repository
    ) {
        parent::__construct($context);
        $this->resourceConnection = $resourceConnection;
        $this->collection = $collection->create();
        $this->attrRepository = $repository;
    }


    /**
     * @param int $categoryId
     * @return array
     */
    public function getCategoryFilterableAttrs($categoryId)
    {
        $conn = $this->resourceConnection->getConnection();
        $select = $conn->select()->from(['ceav' => $conn->getTableName('catalog_eav_attribute')], 'ceav.attribute_id')
            ->join(['eea' => $conn->getTableName('eav_entity_attribute')], 'ceav.attribute_id=eea.attribute_id AND ceav.is_filterable = 1 AND eea.entity_type_id=4')
            ->join(['cpe' => $conn->getTableName('catalog_product_entity')], 'cpe.attribute_set_id=eea.attribute_set_id')
            ->join(['ccp' => $conn->getTableName('catalog_category_product')], 'ccp.product_id=cpe.entity_id')
            ->where('ccp.category_id = ?', $categoryId)
            ->group('ceav.attribute_id');
        return $conn->fetchCol($select);
    }

    /**
     * @param array $attrs
     * @return array
     */
    public function attributesData($attrs)
    {
        $attrData = [];
        $attrs = $this->collection->addFieldToFilter('attribute_id', ['in' => $attrs]);
        foreach ($attrs as $attr) {
            $attrData[$attr->getAttributeId()]['options'] = $this->getAttributeOptions($attr->getAttributeCode());
            $attrData[$attr->getAttributeId()]['attr_code'] = $attr->getAttributeCode();
            $attrData[$attr->getAttributeId()]['attr_id'] = $attr->getAttributeId();
        }
        return $attrData;
    }

    /**
     * @param string $attrCode
     * @return array
     */
    public function getAttributeOptions($attrCode)
    {
        $attribute = $this->getAttributeDataById($attrCode);
        if (!$attribute) {
            return [];
        }
        $attrLabel = $attribute->getAttributeCode();
        $options = $attribute->getOptions();
        $data = [];
        $i = 0;
        foreach ($options as $option) {
            $data[$option->getValue()]['option_label'] = $option->getLabel();
            $data[$option->getValue()]['option_value'] = $option->getValue();
            $data[$option->getValue()]['option_parent'] = $attribute->getAttributeId();
            $data[$option->getValue()]['attr_label'] = $attrLabel;
            $i++;
        }

        return $data;
    }

    /**
     * @param string|int $attrCode
     * @return array|\Magento\Catalog\Api\Data\ProductAttributeInterface|\Magento\Eav\Api\Data\AttributeInterface
     */
    public function getAttributeDataById($attrCode)
    {
        try {
            return $this->attrRepository->get($attrCode);
        } catch (\Exception $exception) {
            return [];
        }
    }


}
