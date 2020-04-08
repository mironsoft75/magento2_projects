<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/

namespace Codilar\ProductImport\Setup;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\Config as CtalogConfig;
use Magento\Catalog\Model\Product\AttributeSet\Options;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\AttributeManagement;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Psr\Log\LoggerInterface;

/**
 * Class UpgradeData
 * @package Magento\TestModule\Setup
 */
class UpgradeData implements UpgradeDataInterface
{

    const ATTR_GROUP = 'Custom Attributes';
    /**
     * @var array
     */
    private $commonAttributes = [
        'customer_friendly_location_name' => [
            'label' => 'Customer Friendly Location Name',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => false,
            'option' => '',
        ],
        'variant_type' => [
            'label' => 'Variant type',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => false,
            'option' => '',
        ]
    ];
    /**
     * @var array
     */
    private $textAreaAttributes = [
        'bom_variant_name' => [
            'label' => 'Bom Variant Name',
            'input' => 'textarea',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => false,
            'option' => '',
        ]
    ];
    /**
     * @var array
     */
    protected $_multiSelectAttributes = [
        'custom_product_type' => [
            'label' => "Product Type",
            'type' => 'varchar',
            'input' => 'multiselect',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => ['values' => ['Close_Setting', 'Colour_Diamond','Open_Setting']],
        ],
        'metal_type' => [
            'label' => "Metal Type",
            'type' => 'varchar',
            'input' => 'multiselect',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => ['values' => ['Gold', 'Platinum']],
        ],
        'karat' => [
            'label' => "Karat",
            'type' => 'varchar',
            'input' => 'multiselect',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => ['values' => ['14','18','22','24','95']],
        ],
        'metal_color' => [
            'label' => "Metal Color",
            'type' => 'varchar',
            'input' => 'multiselect',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => ['values' => ['Yellow', 'White','Rose']],
        ],
        'customer_friendly_location_name' => [
            'label' => "Customer Friendly Location Name",
            'type' => 'varchar',
            'input' => 'multiselect',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => ['values' => ['TS-HYD-HMT', 'TS-HYD-BNJ','TS-HYD']],
        ]
    ];
    /**
     * @var array
     */
    protected $_priceAttributes = [
        'metal_wt' => [
            'label' => "Metal Weight",
            'type' => 'decimal',
            'input' => 'price',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => '',
        ],
        'diamond_wt' => [
            'label' => "Diamond Weight",
            'type' => 'decimal',
            'input' => 'price',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => '',
        ]
    ];
    protected $gstAttribute=[
        'compute_gst' => [
            'label' => 'Compute Gst',
            'input' => 'select',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => false,
            'option' => ['values' => ['Yes', 'No']],
        ],
    ];
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;
    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Options
     */
    private $attributeSets;
    /**
     * @var AttributeManagement
     */
    private $attributeManagement;
    /**
     * @var Config
     */
    private $eavConfig;
    /**
     * @var CtalogConfig
     */
    private $config;
    /**
     * @var ProductAttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * UpgradeData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     * @param CategorySetupFactory $categorySetupFactory
     * @param LoggerInterface $logger
     * @param Options $attributeSets
     * @param AttributeManagement $attributeManagement
     * @param Config $eavConfig
     * @param CtalogConfig $config
     * @param ProductAttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        CategorySetupFactory $categorySetupFactory,
        LoggerInterface $logger,
        Options $attributeSets,
        AttributeManagement $attributeManagement,
        Config $eavConfig,
        CtalogConfig $config,
        ProductAttributeRepositoryInterface $attributeRepository
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->logger = $logger;
        $this->attributeSets = $attributeSets;
        $this->attributeManagement = $attributeManagement;
        $this->eavConfig = $eavConfig;
        $this->config = $config;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            foreach ($this->commonAttributes as $attrCode => $value) {
                $this->createCommonAttributes(
                    $setup,
                    $attrCode,
                    $value['label'],
                    $value['input_type'],
                    $value['input'],
                    self::ATTR_GROUP,
                    $value['backend'],
                    $value['source'],
                    $value['default'],
                    $value['searchable'],
                    $value['filterable'],
                    $value['visible_on_front'],
                    $value['option']
                );
            }
        }
        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            foreach ($this->textAreaAttributes as $attrCode => $value) {
                $this->createTextAreaAttributes(
                    $setup,
                    $attrCode,
                    $value['label'],
                    $value['input_type'],
                    $value['input'],
                    self::ATTR_GROUP,
                    $value['backend'],
                    $value['source'],
                    $value['default'],
                    $value['searchable'],
                    $value['filterable'],
                    $value['visible_on_front'],
                    $value['option']
                );
            }
        }
        if (version_compare($context->getVersion(), '1.0.6') < 0) {
            foreach ($this->_multiSelectAttributes as $attrCode => $value) {
                $this->createMultiSelectAttributes(
                    $setup,
                    $attrCode,
                    $value['label'],
                    $value['type'],
                    $value['input'],
                    self::ATTR_GROUP,
                    $value['source'],
                    $value['default'],
                    $value['searchable'],
                    $value['filterable'],
                    $value['option']
                );
            }
        }
        if (version_compare($context->getVersion(), '1.0.8') < 0) {
            foreach ($this->_priceAttributes as $attrCode => $value) {
                $this->createPriceAttributes(
                    $setup,
                    $attrCode,
                    $value['label'],
                    $value['type'],
                    $value['input'],
                    self::ATTR_GROUP,
                    $value['source'],
                    $value['default'],
                    $value['searchable'],
                    $value['filterable'],
                    $value['option']
                );
            }
        }
        if (version_compare($context->getVersion(), '1.0.9') < 0) {
            foreach ($this->gstAttribute as $attrCode => $value) {
                $this->createGstAttributes(
                    $setup,
                    $attrCode,
                    $value['label'],
                    $value['input_type'],
                    $value['input'],
                    self::ATTR_GROUP,
                    $value['backend'],
                    $value['source'],
                    $value['default'],
                    $value['searchable'],
                    $value['filterable'],
                    $value['visible_on_front'],
                    $value['option']
                );
            }
        }

        $installer->endSetup();
    }

    /**
     * @param $setup
     * @param $code
     * @param $label
     * @param $inputType
     * @param $input
     * @param $group
     * @param null $backend
     * @param null $source
     * @param $option
     */
    private function createCommonAttributes($setup, $code, $label, $inputType, $input, $group, $backend = null, $source = null, $default, $searchable, $filterable, $visible_on_front, $option)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $code);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'group' => $group,
                'type' => $inputType,
                'backend' => $backend,
                'frontend' => '',
                'label' => $label,
                'input' => $input,
                'class' => '',
                'source' => $source,
                'option' => $option,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => $default,
                'searchable' => $searchable,
                'filterable' => $filterable,
                'comparable' => true,
                'visible_on_front' => $visible_on_front,
                'used_in_product_listing' => true,
                'unique' => false
            ]
        );
    }
    /**
     * @param $setup
     * @param $code
     * @param $label
     * @param $inputType
     * @param $input
     * @param $group
     * @param null $backend
     * @param null $source
     * @param $option
     */
    private function createTextAreaAttributes($setup, $code, $label, $inputType, $input, $group, $backend = null, $source = null, $default, $searchable, $filterable, $visible_on_front, $option)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $code);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'group' => $group,
                'type' => $inputType,
                'backend' => $backend,
                'frontend' => '',
                'label' => $label,
                'input' => $input,
                'class' => '',
                'source' => $source,
                'option' => $option,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => $default,
                'searchable' => $searchable,
                'filterable' => $filterable,
                'comparable' => false,
                'visible_on_front' => $visible_on_front,
                'used_in_product_listing' => false,
                'unique' => false
            ]
        );
    }
    /**
     * @param $setup
     * @param $code
     * @param $label
     * @param $inputType
     * @param $input
     * @param $group
     * @param null $source
     * @param $option
     */
    private function createMultiSelectAttributes($setup, $code, $label, $inputType, $input, $group, $source = null,$default,$searchable,$filterable,$option)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $code);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'group' => $group,
                'type' => $inputType,
                'backend' => "Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend",
                'frontend' => '',
                'label' => $label,
                'input' => $input,
                'class' => '',
                'source' => $source,
                'option' => $option,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => $default,
                'searchable' => $searchable,
                'filterable' => $filterable,
                'comparable' => true,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false
            ]
        );
    }
    /**
     * @param $setup
     * @param $code
     * @param $label
     * @param $inputType
     * @param $input
     * @param $group
     * @param null $source
     * @param $option
     */
    private function createPriceAttributes($setup, $code, $label, $inputType, $input, $group, $source = null,$default,$searchable,$filterable,$option)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $code);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'group' => $group,
                'type' => $inputType,
                'backend' => "Magento\Catalog\Model\Product\Attribute\Backend\Price",
                'frontend' => '',
                'label' => $label,
                'input' => $input,
                'class' => '',
                'source' => $source,
                'option' => $option,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => $default,
                'searchable' => $searchable,
                'filterable' => $filterable,
                'comparable' => true,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false
            ]
        );
    }

    /**
     * @param $setup
     * @param $code
     * @param $label
     * @param $inputType
     * @param $input
     * @param $group
     * @param null $backend
     * @param null $source
     * @param $default
     * @param $searchable
     * @param $filterable
     * @param $visible_on_front
     * @param $option
     */
    private function createGstAttributes($setup, $code, $label, $inputType, $input, $group, $backend = null, $source = null, $default, $searchable, $filterable, $visible_on_front, $option)
    {
        /** @var  $eavSetup \Magento\Eav\Setup\EavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $code);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'group' => $group,
                'type' => $inputType,
                'backend' => $backend,
                'frontend' => '',
                'label' => $label,
                'input' => $input,
                'class' => '',
                'source' => $source,
                'option' => $option,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => $default,
                'searchable' => $searchable,
                'filterable' => $filterable,
                'comparable' => true,
                'visible_on_front' => $visible_on_front,
                'used_in_product_listing' => true,
                'unique' => false
            ]
        );
    }

}
