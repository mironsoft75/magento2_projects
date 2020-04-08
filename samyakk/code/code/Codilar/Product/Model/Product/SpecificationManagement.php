<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Product;

use Codilar\Product\Api\Data\Product\AttributesDataInterfaceFactory;
use Codilar\Product\Api\Data\Product\SpecificationGroupInterface;
use Codilar\Product\Api\Data\Product\SpecificationGroupInterfaceFactory;
use Codilar\Product\Api\Product\SpecificationManagementInterface;
use Codilar\Product\Helper\ProductHelper;
use Codilar\Product\Model\Config;
use Codilar\Product\Model\Pool\SpecificationsPool;
use Magento\Catalog\Model\Config as CatalogConfig;
use Magento\Eav\Api\AttributeSetRepositoryInterface;

class SpecificationManagement implements SpecificationManagementInterface
{
    /**
     * @var SpecificationsPool
     */
    private $specificationsPool;
    /**
     * @var AttributesDataInterfaceFactory
     */
    private $attributesDataInterfaceFactory;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * @var CatalogConfig
     */
    private $catalogConfig;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var AttributeSetRepositoryInterface
     */
    private $attributeSetRepository;
    /**
     * @var SpecificationGroupInterfaceFactory
     */
    private $specificationGroupInterfaceFactory;

    /**
     * SpecificationManagement constructor.
     * @param SpecificationsPool $specificationsPool
     * @param AttributesDataInterfaceFactory $attributesDataInterfaceFactory
     * @param ProductHelper $productHelper
     * @param CatalogConfig $catalogConfig
     * @param Config $config
     * @param AttributeSetRepositoryInterface $attributeSetRepository
     * @param SpecificationGroupInterfaceFactory $specificationGroupInterfaceFactory
     */
    public function __construct(
        SpecificationsPool $specificationsPool,
        AttributesDataInterfaceFactory $attributesDataInterfaceFactory,
        ProductHelper $productHelper,
        CatalogConfig $catalogConfig,
        Config $config,
        AttributeSetRepositoryInterface $attributeSetRepository,
        SpecificationGroupInterfaceFactory $specificationGroupInterfaceFactory
    ) {
        $this->specificationsPool = $specificationsPool;
        $this->attributesDataInterfaceFactory = $attributesDataInterfaceFactory;
        $this->productHelper = $productHelper;
        $this->catalogConfig = $catalogConfig;
        $this->config = $config;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->specificationGroupInterfaceFactory = $specificationGroupInterfaceFactory;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Codilar\Product\Api\Data\Product\SpecificationGroupInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductSpecification($product)
    {
        $specificationGroups = $this->getSpecifications($product);
        $response = [];
        foreach ($specificationGroups as $specificationGroup) {
            /** @var SpecificationGroupInterface $specificationData */
            $specificationData = $this->specificationGroupInterfaceFactory->create();
            $specifications = [];
            foreach ($specificationGroup['attributes'] as $specification) {
                /** @var \Codilar\Product\Api\Data\Product\AttributesDataInterface $attribute */
                $attribute = $this->attributesDataInterfaceFactory->create();
                $specifications[] = $attribute->setLabel($specification['label'])
                    ->setCode($specification['code'])
                    ->setValue($specification['value']);
            }
            if (count($specifications) > 0) {
                $specificationData->setLabel($specificationGroup['label'])
                    ->setAttributes($specifications);
                $response[] = $specificationData;
            }
        }

        return $response;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getSpecifications($product)
    {
        $specifications = $this->specificationsPool->getSpecifications();
        $attributeSetId = $product->getAttributeSetId();
        $groupId = $this->catalogConfig->getAttributeGroupId($attributeSetId, 'Attributes');
        if ($groupId) {
            /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
            foreach ($product->getAttributes() as $attribute) {
                if ($attribute->isInGroup($attributeSetId, $groupId)) {
                    $specifications[] = $attribute->getAttributeCode();
                }
            }
        }
        $response = [];
        $specificationConfiguration = $this->config->getSpecificationJson()['attribute_set'];
        $productAttributeSetName = $this->attributeSetRepository->get($attributeSetId)->getAttributeSetName();
        $currentSpecificationConfigurationIndex = array_search($productAttributeSetName, array_column($specificationConfiguration, 'label'));
        if ($currentSpecificationConfigurationIndex >= 0) {
            $currentSpecificationConfiguration = $specificationConfiguration[$currentSpecificationConfigurationIndex];
            foreach ($currentSpecificationConfiguration['groups'] as $group) {
                $response[] = [
                    'label' => $group['label'],
                    'attributes' => $this->productHelper->getAttributesData($group['attributes'], $product)
                ];
            }
        }
        return $response;
    }
}
