<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/
namespace Codilar\Base\Setup;

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
use Codilar\Base\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * Class UpgradeData
 * @package Magento\TestModule\Setup
 */
class UpgradeData implements UpgradeDataInterface
{

    const ATTR_FILE = 'ProductCategoryAttributes.csv';
    const ATTR_GROUP = 'Custom Attributes';
    const ATTR_GROUP_ACES = 'ACES Attributes';
    const DEFAULT_NAME_CODE = 'default_name';
    const DEFAULT_NAME_LABEL = 'Default Name';
    /**
     * @var array
     */
    private $commonAttributes = [
        'design_id' => [
            'label' => 'Design Id',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => true,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => '',
        ],
        'variant' => [
            'label' => 'Variant',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => true,
            'filterable' => false,
            'visible_on_front' => false,
            'option' => '',
        ],
        'tags' => [
            'label' => 'Tags',
            'input' => 'textarea',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => true,
            'filterable' => false,
            'visible_on_front' => false,
            'option' => '',
        ],
        'certificate_name' => [
            'label' => 'Certificate Name',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => true,
            'filterable' => true,
            'visible_on_front' => true,
            'option' => '',
        ],
        'height' => [
            'label' => 'Height',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'visible_on_front' => true,
            'option' => '',
        ],
        'width' => [
            'label' => 'Width',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'visible_on_front' => true,
            'option' => '',
        ],
        'collection' => [
            'label' => 'Collection',
            'input' => 'multiselect',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'visible_on_front' => true,
            'option' => ['values' => ['Etched', 'Signature','Starbust','Peacock','Lovebirds','Style by Ami','Aaranya','Bombay Deco','Butterfly-The Sprit of you','Enchanted','Jaipur','Banarasi Brocade','New Expressions of love','Madhubani','stitched','Noor e kashmir','Bee Jewelled','Weave','Diorama','Zyrah','Starfire','Ornati by Farah Khan','Gold Crush','Gold Struck','My First Diamond','Gold Lace','Valentines','paisley','Fleur','La naturale']],
        ],
        'gender' => [
            'label' => 'Gender',
            'input' => 'multiselect',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'visible_on_front' => true,
            'option' => ['values' => ['Male', 'Female','Other']],
        ],
        'ad_products' => [
            'label' => 'Ad Products',
            'input' => 'select',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'visible_on_front' => false,
            'option' => ['values' => ['Yes', 'No']],
        ],
        'occasion' => [
            'label' => 'Occasion',
            'input' => 'multiselect',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'visible_on_front' => true,
            'option' => ['values' => ['Wedding', 'Anniversary','Evening','Office Wear','Engagement','Everyday','Desk to dinner','Party Wear','Gifting','Rakshabandhan','Party','Birthday','Valentine_day']],
        ],
        'chain_type' => [
            'label' => 'Chain Type',
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
        'finish' => [
            'label' => 'Finish',
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
        'is_mount' => [
            'label' => 'Is Mount',
            'input' => 'select',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => true,
            'option' => ['values' => ['Yes', 'No']],
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
            'visible_on_front' => false,
            'option' => '',
        ],
        'manufacturing_days' => [
            'label' => 'Manufacturing Days',
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
        'metal_type' => [
            'label' => 'Metal Type',
            'input' => 'select',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'visible_on_front' => true,
            'option' => ['values' => ['Gold', 'Platinum']],
        ],
        'metal_color' => [
            'label' => 'Metal Color',
            'input' => 'multiselect',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'visible_on_front' => true,
            'option' => ['values' => ['Yellow', 'White','Rose']],
        ],
        'metal_karat' => [
            'label' => 'Metal Karat',
            'input' => 'select',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'visible_on_front' => true,
            'option' => ['values' => ['14k', '18k','22k','24k']],
        ],
        'gross_weight' => [
            'label' => 'Gross Weight',
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
        'labour_rate' => [
            'label' => 'Labour Rate',
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
        'wastage_percentage' => [
            'label' => 'Wastage Percentage',
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
        'wastage_weight' => [
            'label' => 'Wastage Weight',
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
           'weight_unit' => [
            'label' => 'Weight Unit',
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
        'resizable_product' => [
            'label' => 'Resizable Product',
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
        'lower_size_limit' => [
            'label' => 'Lower Size Limit',
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
        'upper_size_limit' => [
            'label' => 'Upper Size Limit',
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
        'stone_information' => [
            'label' => 'Stone Information',
            'input' => 'textarea',
            'input_type' => 'text',
            'source' => '',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'visible_on_front' => false,
            'option' => '',
        ],
        'internal_stone_name' => [
            'label' => 'Internal Stone Name',
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
        'default_diamond_quality' => [
            'label' => 'Default Diamond Quality',
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
        'fast_delivery' => [
            'label' => 'Fast delivery',
            'input' => 'select',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'visible_on_front' => false,
            'option' => ['values' => ['Yes', 'No']],
        ],
        'erp_refno' => [
            'label' => 'Erp RefNo',
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
    private $qualityAttributes = [
        'diamond_quality' => [
            'label' => 'Diamond Quality',
            'input' => 'select',
            'input_type' => 'text',
            'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Table',
            'backend' => '',
            'visible_on_front' => false,
            'option' => ['values' => ['EF VVS', 'GH VVS']],
        ]
        ];

    protected $_multiSelectAttributes = [
        'collection' => [
            'label' => "Collection",
            'type' => 'varchar',
            'input' => 'multiselect',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => ['values' => ['Etched', 'Signature','Starbust','Peacock','Lovebirds','Style by Ami','Aaranya','Bombay Deco','Butterfly-The Sprit of you','Enchanted','Jaipur','Banarasi Brocade','New Expressions of love','Madhubani','stitched','Noor e kashmir','Bee Jewelled','Weave','Diorama','Zyrah','Starfire','Ornati by Farah Khan','Gold Crush','Gold Struck','My First Diamond','Gold Lace','Valentines','paisley','Fleur','La naturale']],
        ],
        'gender' => [
            'label' => "Gender",
            'type' => 'varchar',
            'input' => 'multiselect',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => ['values' => ['Male', 'Female','Other']],
        ],
        'occasion' => [
            'label' => "Occasion",
            'type' => 'varchar',
            'input' => 'multiselect',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => ['values' => ['Wedding', 'Anniversary','Evening','Office Wear','Engagement','Everyday','Desk to dinner','Party Wear','Gifting','Rakshabandhan','Party','Birthday','Valentine_day']],
        ],
        'metal_color' => [
            'label' => "Metal Color",
            'type' => 'varchar',
            'input' => 'multiselect',
            'source' => "\Magento\Eav\Model\Entity\Attribute\Source\Table",
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'option' => ['values' => ['Yellow Gold', 'White Gold','Rose Gold']],
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
     * @var
     */
    private $attributeSet;
    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;
    /**
     * @var Data
     */
    private $helper;
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
     * @param Data $helper
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
        Data $helper,
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
        $this->helper = $helper;
        $this->logger = $logger;
        $this->attributeSets = $attributeSets;
        $this->attributeManagement = $attributeManagement;
        $this->eavConfig = $eavConfig;
        $this->config = $config;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.2.7') < 0) {
            foreach ($this->qualityAttributes as $attrCode => $value) {
                $this->createQualityAttributes(
                    $setup,
                    $attrCode,
                    $value['label'],
                    $value['input_type'],
                    $value['input'],
                    self::ATTR_GROUP,   
                    $value['backend'],
                    $value['source'],
                    $value['visible_on_front'],
                    $value['option']
                );
            }
        }
        if (version_compare($context->getVersion(), '1.2.7') < 0) {
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
        if (version_compare($context->getVersion(), '1.2.7') < 0) {
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
    private function createCommonAttributes($setup, $code, $label, $inputType, $input, $group, $backend = null, $source = null,$default,$searchable,$filterable,$visible_on_front,$option)
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
     * @param null $backend
     * @param null $source
     * @param $option
     */
    private function createQualityAttributes($setup, $code, $label, $inputType, $input, $group, $backend = null, $source = null,$visible_on_front,$option)
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
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => true,
                'filterable' => true,
                'comparable' => true,
                'visible_on_front' => $visible_on_front,
                'used_in_product_listing' => true,
                'unique' => false
            ]
        );
    }
}
