<?php

namespace Pimcore\ImportExport\Helper;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory;
use Magento\Eav\Model\Entity\Attribute\Source\TableFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;

class AttributeOptionsHelper extends \Magento\Framework\App\Helper\AbstractHelper
{
    CONST YMM_ATTRIBUTES = ['year_id', 'make_name', 'model_name'];
    /**
     * @var ProductAttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var array
     */
    protected $attributeValues;

    /**
     * @var TableFactory
     */
    protected $tableFactory;

    /**
     * @var AttributeOptionManagementInterface
     */
    protected $attributeOptionManagement;

    /**
     * @var AttributeOptionLabelInterfaceFactory
     */
    protected $optionLabelFactory;

    /**
     * @var AttributeOptionInterfaceFactory
     */
    protected $optionFactory;
    /**
     * @var ProductFactory
     */
    private $productFactory;
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * Data constructor.
     *
     * @param Context                              $context
     * @param ProductAttributeRepositoryInterface  $attributeRepository
     * @param TableFactory                         $tableFactory
     * @param AttributeOptionManagementInterface   $attributeOptionManagement
     * @param AttributeOptionLabelInterfaceFactory $optionLabelFactory
     * @param AttributeOptionInterfaceFactory      $optionFactory
     */
    public function __construct(
        Context $context,
        ProductAttributeRepositoryInterface $attributeRepository,
        TableFactory $tableFactory,
        AttributeOptionManagementInterface $attributeOptionManagement,
        AttributeOptionLabelInterfaceFactory $optionLabelFactory,
        AttributeOptionInterfaceFactory $optionFactory,
        ProductFactory $productFactory,
        UrlInterface $url
    )
    {
        parent::__construct($context);

        $this->attributeRepository = $attributeRepository;
        $this->tableFactory = $tableFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->optionLabelFactory = $optionLabelFactory;
        $this->optionFactory = $optionFactory;
        $this->productFactory = $productFactory;
        $this->url = $url;
    }

    /**
     * Get attribute by code.
     *
     * @param string $attributeCode
     * @return \Magento\Catalog\Api\Data\ProductAttributeInterface
     */
    public function getAttribute($attributeCode)
    {
        return $this->attributeRepository->get($attributeCode);
    }

    public function processProductOptionId($attributeCode, $label)
    {
        if (strlen($label) < 1) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Label for %1 must not be empty.', $attributeCode)
            );
        }
        $optionId = $this->getAttribute($attributeCode)->getSource()->getOptionId($label);
        if (!$optionId) {
            //$this->_logger->info($attributeCode . ' - ' . $label . ' No option id so creating');
            /** @var \Magento\Eav\Model\Entity\Attribute\OptionLabel $optionLabel */
            $optionLabel = $this->optionLabelFactory->create();
            $optionLabel->setStoreId(0);
            $optionLabel->setLabel($label);
            $option = $this->optionFactory->create();
            $option->setLabel($optionLabel);
            $option->setStoreLabels([$optionLabel]);
            $option->setSortOrder(0);
            $option->setIsDefault(false);

            $this->attributeOptionManagement->add(
                \Magento\Catalog\Model\Product::ENTITY,
                $this->getAttribute($attributeCode)->getAttributeId(),
                $option
            );

            $optionId = $this->getAttribute($attributeCode)->getSource()->getOptionId($label);
        }
        //$this->_logger->info($attributeCode . ' - ' . $label . ' - ' . $optionId);
        return $optionId;
    }

    /**
     * Find or create a matching attribute option
     *
     * @param string $attributeCode Attribute the option should exist in
     * @param string $label Label to find or add
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createOrGetId($attributeCode, $label)
    {
        if (strlen($label) < 1) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Label for %1 must not be empty.', $attributeCode)
            );
        }

        // Does it already exist?
        //$optionId = $this->getOptionId($attributeCode, $label);
        $optionId = $this->getProductAttributeOptionId($attributeCode, $label);
        /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
        //$attribute = $this->getAttribute($attributeCode);
        //$attribute = $this->productFactory->create()->getResource()->getAttribute($attributeCode);
        //$optionId = $attribute->getSource()->getOptionId($label);
        if (!$optionId) {
            // If no, add it.

            /** @var \Magento\Eav\Model\Entity\Attribute\OptionLabel $optionLabel */
            $optionLabel = $this->optionLabelFactory->create();
            $optionLabel->setStoreId(0);
            $optionLabel->setLabel($label);

            $option = $this->optionFactory->create();
            $option->setLabel($optionLabel);
            $option->setStoreLabels([$optionLabel]);
            $option->setSortOrder(0);
            $option->setIsDefault(false);

            $this->attributeOptionManagement->add(
                \Magento\Catalog\Model\Product::ENTITY,
                $this->getAttribute($attributeCode)->getAttributeId(),
                $option
            );

            $optionId = $this->getProductAttributeOptionId($attributeCode, $label, true);
        }

        return $optionId;
    }

    /**
     * @param $attribute
     * @param $label
     * @return null|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductAttributeOptionId($attribute, $label)
    {
        $optionId = false;
        $poductReource = $this->productFactory->create();
        $attr = $poductReource->getAttribute($attribute);
        if ($attr && $attr->usesSource()) {
            $optionId = $attr->getSource()->getOptionId($label);
        }
        return $optionId;
    }

    /**
     * Find the ID of an option matching $label, if any.
     *
     * @param string $attributeCode Attribute code
     * @param string $label Label to find
     * @param bool   $force If true, will fetch the options even if they're already cached.
     * @return int|false
     */
    public function getOptionId($attributeCode, $label, $force = false)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
        $attribute = $this->getAttribute($attributeCode);

        // Build option array if necessary
        if ($force === true || !isset($this->attributeValues[$attribute->getAttributeId()])) {
            $this->attributeValues[$attribute->getAttributeId()] = [];

            // We have to generate a new sourceModel instance each time through to prevent it from
            // referencing its _options cache. No other way to get it to pick up newly-added values.

            /** @var \Magento\Eav\Model\Entity\Attribute\Source\Table $sourceModel */
            $sourceModel = $this->tableFactory->create();
            $sourceModel->setAttribute($attribute);

            foreach ($sourceModel->getAllOptions() as $option) {
                $this->attributeValues[$attribute->getAttributeId()][$option['label']] = $option['value'];
            }
        }

        // Return option ID if exists
        if (isset($this->attributeValues[$attribute->getAttributeId()][$label])) {
            return $this->attributeValues[$attribute->getAttributeId()][$label];
        }

        // Return false if does not exist
        return false;
    }

    /**
     * @param $params
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getYmmOptionUrl($params)
    {
        $ymmAttributes = self::YMM_ATTRIBUTES;
        $query = [];
        foreach ($ymmAttributes as $attributeCode) {
            if (isset($params[$attributeCode]) && !empty($params[$attributeCode])) {
                $query[$attributeCode] = $this->createOrGetId($attributeCode, $params[$attributeCode]);
            }
        }
        return $this->url->getUrl('all-products', ['_query' => $query]);
    }
}