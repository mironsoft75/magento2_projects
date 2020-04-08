<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21/5/19
 * Time: 1:21 PM
 */

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
use Magento\Framework\Setup\InstallDataInterface;
use Psr\Log\LoggerInterface;

/**
 * Class InstallData
 * @package Codilar\ProductImport\Setup
 */
class InstallData implements InstallDataInterface
{
    const ATTR_GROUP = 'Custom Attributes';

    /**
     * @var array
     */
    private $commonAttributes = [
        'stock_type' => [
            'label' => 'Stock Type',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'item_name' => [
            'label' => 'Item Name',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'variant_name' => [
            'label' => 'Variant Name',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'karat_color' => [
            'label' => 'Karat Color',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'stock_code' => [
            'label' => 'Stock Code',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'location_name' => [
            'label' => 'Location Name',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'dept' => [
            'label' => 'Dept',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'party' => [
            'label' => 'Party',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'vendor_party' => [
            'label' => 'Vendor Party',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'owner_party' => [
            'label' => 'Owner Party',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'pcs' => [
            'label' => 'Pcs',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'total_weight' => [
            'label' => 'Total Weight',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'net_weight' => [
            'label' => 'Net Weight',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'pg_weight' => [
            'label' => 'PG Wt',
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
        'metal_rate' => [
            'label' => 'Metal Rate',
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
        'bom_variant_name' => [
            'label' => 'Bom Variant Name',
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
        'stone_pcs' => [
            'label' => 'Stone Pcs',
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

        'stone_weight' => [
            'label' => 'Stone Weight',
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
        'stone_rate' => [
            'label' => 'Stone Rate',
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
        'stock_value' => [
            'label' => 'Stock Value',
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
        'labour_value' => [
            'label' => 'Labour Value',
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
        'wastage' => [
            'label' => 'Wastage',
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
        'price_override' => [
            'label' => 'Price OverRide',
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
        'delete_product' => [
            'label' => 'Delete Product',
            'input' => 'select',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => false,
            'option' => ['values' => ['Yes', 'No']],
        ]
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
     * InstallData constructor.
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
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
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
     * @param $default
     * @param $searchable
     * @param $filterable
     * @param $visible_on_front
     * @param $option
     */
    private function createCommonAttributes($setup, $code, $label, $inputType, $input, $group, $backend = null, $source = null, $default, $searchable, $filterable, $visible_on_front, $option)
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