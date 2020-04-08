<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Evincemage\Rapnet\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    private $attributeSetFactory;

    private $categorySetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        categorySetupFactory $categorySetupFactory
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

        $attributeSet = $this->attributeSetFactory->create();
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

        $data = [
            'attribute_set_name' => 'Diamond Product',
            'entity_type_id' => $entityTypeId,
            'sort_order' => 20,
        ];
        $attributeSet->setData($data);
        $attributeSet->validate();
        $attributeSet->save();
        $attributeSet->initFromSkeleton($attributeSetId);
        $attributeSet->save();

        // TO CREATE PRODUCT ATTRIBUTE
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_shape',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Shape',
                'backend' => '',
                'input' => 'select',
                'source' => 'Evincemage\Rapnet\Model\Config\Source\Options\Shape',
                'required' => false,
                'sort_order' => 10,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_certificates',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Certificate',
                'backend' => '',
                'input' => 'select',
                'source' => 'Evincemage\Rapnet\Model\Config\Source\Options\Certificates',
                'required' => false,
                'sort_order' => 20,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_carats',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Carat',
                'backend' => '',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'sort_order' => 30,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_clarity',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Clarity',
                'backend' => '',
                'input' => 'select',
                'source' => 'Evincemage\Rapnet\Model\Config\Source\Options\Clarity',
                'required' => false,
                'sort_order' => 40,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_color',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Color',
                'backend' => '',
                'input' => 'select',
                'source' => 'Evincemage\Rapnet\Model\Config\Source\Options\Color',
                'required' => false,
                'sort_order' => 50,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_cut',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Cut',
                'backend' => '',
                'input' => 'select',
                'source' => 'Evincemage\Rapnet\Model\Config\Source\Options\Cut',
                'required' => false,
                'sort_order' => 60,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_polish',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Polish',
                'backend' => '',
                'input' => 'select',
                'source' => 'Evincemage\Rapnet\Model\Config\Source\Options\Polish',
                'required' => false,
                'sort_order' => 70,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_symmetry',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Symmetry',
                'backend' => '',
                'input' => 'select',
                'source' => 'Evincemage\Rapnet\Model\Config\Source\Options\Symmetry',
                'required' => false,
                'sort_order' => 80,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_table',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Table',
                'backend' => '',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'sort_order' => 90,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_depth',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Depth',
                'backend' => '',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'sort_order' => 100,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_measurements',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Measurements',
                'backend' => '',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'sort_order' => 110,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_fluorescence',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Fluorescence',
                'backend' => '',
                'input' => 'select',
                'source' => 'Evincemage\Rapnet\Model\Config\Source\Options\Fluorescence',
                'required' => false,
                'sort_order' => 130,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_lab',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Lab',
                'backend' => '',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'sort_order' => 150,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'rapnet_diamond_certimg',
            [
                'group' => 'Diamond Properties',
                'type' => 'varchar',
                'label' => 'Certificate Image',
                'backend' => '',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'sort_order' => 160,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true
            ]
        );

        $setup->endSetup();
    }

}