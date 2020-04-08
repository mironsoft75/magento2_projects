<?php
/**
 * Created by salman.
 * Date: 7/2/18
 * Time: 5:05 PM
 * Filename: UpgradeData.php
 */

namespace Pimcore\Stores\Setup;

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
use Pimcore\Stores\Helper\Data;
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

    private $ymmMultiSelectAttributes = [
        'base_vehicle_id' => [
            'label' => 'Base vehicle ID',
        ],
        'year_id' => [
            'label' => 'Year',
        ],
        'make_name' => [
            'label' => 'Make',
        ],
        'model_name' => [
            'label' => 'Model',
        ],
        'sub_model' => [
            'label' => 'Sub Model Name',
        ],
        'sub_detail' => [
            'label' => 'Sub Detail',
        ],
        'body_type' => [
            'label' => 'Body Type',
        ],
        'bed_type' => [
            'label' => 'Bed Type',
        ],
        'cab_type' => [
            'label' => 'Cab Type',
        ],
        'bed_length' => [
            'label' => 'Bed Length',
        ],
        'no_of_doors' => [
            'label' => 'No of Doors',
        ],
        'fitment_position' => [
            'label' => 'Position',
        ],
        'style' => [
            'label' => 'Style'
        ],
        'material' => [
            'label' => 'Material'
        ],
        'brand_name' => [
            'label' => 'Brand Name'
        ],
        'popularity_code' => [
            'label' => 'Popularity Code'
        ]
    ];

    private $commonAttributes = [
        'prop65' => [
            'label' => 'Prop 65',
            'input' => 'boolean',
            'input_type' => 'int',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'backend' => ''
        ],
        'finish' => [
            'label' => 'Finish',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'sold_as' => [
            'label' => 'Sold As',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'prop65_short_form_warning' => [
            'label' => 'Prop 65 Short Form Warning',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'prop65_chemical' => [
            'label' => 'Prop 65 Chemical',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'installation_sheet' => [
            'label' => 'Installation Sheet',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'warranty_sheet' => [
            'label' => 'Warranty Sheet',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'has_aces' => [
            'label' => 'Has Aces',
            'input' => 'boolean',
            'input_type' => 'int',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'backend' => ''
        ],
        'brand_name' => [
            'label' => 'Brand Name',
            'input' => 'multiselect',
            'input_type' => 'text',
            'source' => '',
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend'
        ],
        'popularity_code' => [
            'label' => 'Popularity code',
            'input' => 'select',
            'input_type' => 'text',
            'source' => '',
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend'
        ],
        'price_ret' => [
            'label' => 'RET Price',
            'input' => 'price',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'price_map' => [
            'label' => 'MAP price',
            'input' => 'price',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'price_jbr' => [
            'label' => 'JBR price',
            'input' => 'price',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'msrp_sales_sort' => [
            'label' => 'MSRP sales sort',
            'input' => 'price',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'class_description' => [
            'label' => 'Class Description',
            'input' => 'text',
            'input_type' => 'text',
            'source' => '',
            'backend' => ''
        ],
        'sales_rank' => [
            'label' => 'Sales Rank',
            'input' => 'text',
            'input_type' => 'int',
            'source' => '',
            'backend' => ''
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
        // TODO: Implement upgrade() method.
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            //Import attributes and sets based on category mapping
            $this->importProductCategoryAttributes($setup);

            //Create YMM attributes
            $this->createYmmProductAttributes($setup);
        }

        if (version_compare($context->getVersion(), '1.0.6') < 0) {
            foreach ($this->commonAttributes as $attrCode => $value) {
                $this->createCommonAttributes(
                    $setup,
                    $attrCode,
                    $value['label'],
                    $value['input_type'],
                    $value['input'],
                    self::ATTR_GROUP,
                    $value['backend'],
                    $value['source']
                );
            }
        }

        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            //Updated YMM based on new reqs
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $defaultAttributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $categorySetup->addAttributeGroup($entityTypeId, $defaultAttributeSetId, self::ATTR_GROUP_ACES, 19);
            $this->createYmmProductAttributes($setup);
        }

        if (version_compare($context->getVersion(), '1.0.7') < 0) {
            //remove 'position' attribute as it is part of magento system attrigutes
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'position');
            //Create YMM attributes
            $this->createYmmProductAttributes($setup);
        }

        if (version_compare($context->getVersion(), '1.0.8') < 0) {
            //remove alsaka(AK,US) and hawaii(HI,US) regions from directory_country_region
            $installer->run("DELETE FROM `directory_country_region` WHERE `country_id` = 'US' AND `code`='HI'");
            $installer->run("DELETE FROM `directory_country_region` WHERE `country_id` = 'US' AND `code`='AK'");
        }

        if (version_compare($context->getVersion(), '1.0.9') < 0) {
            $this->removeYmmAttributes($setup);
        }

        if (version_compare($context->getVersion(), '1.0.10') < 0) {
            $this->assignAttributesToAllSets($setup, $this->ymmMultiSelectAttributes, self::ATTR_GROUP_ACES);
            $this->assignAttributesToAllSets($setup, $this->commonAttributes, self::ATTR_GROUP);
        }

        if(version_compare($context->getVersion(), '1.0.11') < 0){
            $this->createCategoryAttribute($setup,self::DEFAULT_NAME_CODE,self::DEFAULT_NAME_LABEL);
        }

        $installer->endSetup();
    }

    private function createCommonAttributes($setup, $code, $label, $inputType, $input, $group, $backend = null, $source = null)
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
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => true,
                'filterable' => true,
                'comparable' => true,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false
            ]
        );
    }

    private function assignAttributeToSets($attributeCode, $attributeSetId)
    {
        $groupId = $this->config->getAttributeGroupId($attributeSetId, self::ATTR_GROUP);
        $this->attributeManagement->assign(
            \Magento\Catalog\Model\Product::ENTITY,
            $attributeSetId,
            $groupId,
            $attributeCode,
            null
        );
    }

    /**
     * @param $setup
     */
    private function importProductCategoryAttributes($setup)
    {
        try {
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $defaultAttributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

            $categorySetup->addAttributeGroup($entityTypeId, $defaultAttributeSetId, self::ATTR_GROUP, 20);

            $fileData = $this->helper->readCsvFile(self::ATTR_FILE);
            $categoryName = $fileData[0];
            $catClassDesc = $fileData[1];
            $partTermId = $fileData[2];
            //$labels = $fileData[3];
            $attributeSets = array_map(array($this, 'implodePipeChar'), $categoryName, $catClassDesc, $partTermId);
            $existingAttributeSets = $this->getAttributeSetsList();
            $createdAttributeSet = [];
            echo "\n";
            echo "Creating Attribute Sets from CSV \n";
            for ($i = 1; $i <= (count($attributeSets) - 1); $i++) {
                $attributeSetName = $attributeSets[$i];
                if (!in_array($attributeSetName, $existingAttributeSets)) {
                    $createdAttributeSet[$i] = $this->createAttributeSet($defaultAttributeSetId, $entityTypeId, $attributeSetName, $i);
                }

            }
            echo "Done \n";
            unset($fileData[0]);
            unset($fileData[1]);
            unset($fileData[2]);
            unset($fileData[3]);
            $attributes = array_values($fileData);
            echo "Creating Attributes from CSV \n";
            foreach ($attributes as $attributeData) {
                $attribute = '';
                for ($i = 0; $i <= (count($attributeData) - 1); $i++) {
                    if ($i == 0) {
                        $attribute = $attributeData[$i];
                        $this->createProductTextAttribute($setup, $this->camelCaseToUnderscore($attribute), $attribute);
                    } else {
                        if ($attributeData[$i] == 'Y') {
                            $this->assignAttributeToSets($this->camelCaseToUnderscore($attribute), $createdAttributeSet[$i]['attribute_set_id']);
                        }
                    }

                }
            }
            echo "Done \n";

        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->logger->critical($e);
        }
    }

    /**
     * @return array
     */
    private function getAttributeSetsList()
    {
        $attributeSets = $this->attributeSets->toOptionArray();
        $attributeSetsData = [];
        if (count($attributeSets)) {
            foreach ($attributeSets as $attributeSet) {
                $attributeSetsData[] = $attributeSet['label'];
            }
        }
        return $attributeSetsData;
    }

    /**
     * @param $input
     * @return string
     */
    private function camelCaseToUnderscore($input)
    {
        $input = str_replace(' ', '', $input);
        $input = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
        $input = (strlen($input) > 30) ? substr($input, 0, 30) : $input;
        return $input;
    }

    /**
     * @param $v1
     * @param $v2
     * @param $v3
     * @return string
     */
    private function implodePipeChar($v1, $v2, $v3)
    {
        return $data = $v1 . '|' . $v2 . '|' . $v3;
    }

    /**
     * @param $defaultAttributeSetId
     * @param $entityTypeId
     * @param $attributeSetName
     * @param $sortOrder
     * @return \Magento\Eav\Model\Entity\Attribute\Set
     */
    private function createAttributeSet($defaultAttributeSetId, $entityTypeId, $attributeSetName, $sortOrder)
    {
        $data = [
            'attribute_set_name' => $attributeSetName,
            'entity_type_id' => $entityTypeId,
            'sort_order' => $sortOrder,
        ];
        $attributeSet = $this->attributeSetFactory->create();
        $attributeSet->setData($data);
        $attributeSet->validate();
        $attributeSet->save();
        $attributeSet->initFromSkeleton($defaultAttributeSetId);
        $attributeSet->save();
        $createdAttributeSet = [
            'attribute_set_id' => $attributeSet->getId(),
            'attribute_set_name' => $attributeSet->getAttributeSetName()
        ];


        return $createdAttributeSet;

    }

    /**
     * @param        $setup
     * @param        $attributeCode
     * @param        $attributeLabel
     * @param string $attibuteSet
     */
    private function createProductTextAttribute($setup, $attributeCode, $attributeLabel)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $attributeCode,
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => $attributeLabel,
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => true,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );
    }

    private function removeYmmAttributes($setup)
    {
        $ymmAttributes = $this->ymmMultiSelectAttributes;
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        foreach ($ymmAttributes as $code => $data) {
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $code);
        }
    }

    private function createYmmProductAttributes($setup)
    {
        $ymmAttributes = $this->ymmMultiSelectAttributes;
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        //echo "Creating YMM Attributes \n";
        foreach ($ymmAttributes as $code => $data) {
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $code);
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $code,
                [
                    'group' => self::ATTR_GROUP_ACES,
                    'type' => 'varchar',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'frontend' => '',
                    'label' => $data['label'],
                    'input' => 'multiselect',
                    'class' => '',
                    'source' => '',
                    /* Source of your select type custom attribute options*/
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    /*Scope of your attribute */
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '',
                    'searchable' => true,
                    'filterable' => true,
                    'comparable' => true,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false
                ]
            );
        }
        //echo "Done \n";

    }

    private function createCategoryAttribute($setup,$code,$label)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            $code,
            [
                'type'         => 'varchar',
                'label'        => $label,
                'input'        => 'text',
                'sort_order'   => 100,
                'source'       => '',
                'global'       => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible'      => true,
                'required'     => false,
                'user_defined' => false,
                'default'      => null,
                'group'        => '',
                'backend'      => ''
            ]
        );
    }

    private function assignAttributesToAllSets($setup, $attributes, $groupCode)
    {
        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);
        foreach ($attributes as $attributeCode => $data) {
            if(!$eavSetup->getAttributeId($entityTypeId,$attributeCode)){
                continue;
            }
            foreach ($attributeSetIds as $attributeSetId) {
                $groupId = $this->config->getAttributeGroupId($attributeSetId, $groupCode);
                $this->attributeManagement->assign(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attributeSetId,
                    $groupId,
                    $attributeCode,
                    null
                );
            }
        }
    }


}
