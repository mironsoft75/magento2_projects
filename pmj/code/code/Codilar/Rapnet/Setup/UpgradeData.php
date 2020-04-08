<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13/2/19
 * Time: 11:28 AM
 */

namespace Codilar\Rapnet\Setup;

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

class UpgradeData implements UpgradeDataInterface
{
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
        $this->logger = $logger;
        $this->attributeSets = $attributeSets;
        $this->attributeManagement = $attributeManagement;
        $this->eavConfig = $eavConfig;
        $this->config = $config;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param $setup
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeRapnetAttribute($setup)
    {

        try {
            $attributes = ['rapnet_diamond_shape', 'rapnet_diamond_certificates', 'rapnet_diamond_carats', 'rapnet_diamond_clarity',
                'rapnet_diamond_color', 'rapnet_diamond_cut', 'rapnet_diamond_polish', 'rapnet_diamond_symmetry', 'rapnet_diamond_table', 'rapnet_diamond_depth',
                'rapnet_diamond_measurements', 'rapnet_diamond_fluorescence', 'rapnet_diamond_lab', 'rapnet_diamond_certimg'];
            /**
             * @var $eavSetup \Magento\Eav\Setup\EavSetup
             */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            foreach ($attributes as $attribute) {
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $attribute);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);

        }

    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addRapnetAttribute(ModuleDataSetupInterface $setup)
    {

        try {
            $setup->startSetup();

            // TO CREATE PRODUCT ATTRIBUTE
            /**
             * @var $eavSetup \Magento\Eav\Setup\EavSetup
             */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_shape',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Shape',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'select',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 10,
                    'is_user_defined' => true,
                    'user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'option' => ['values' => ['Round', 'Princess', 'Emerald', 'Radiant', 'Cushion', 'Pear', 'Marquise', 'Oval', 'Asscher', 'Heart', 'Trillion']],
                    'system' => false,
                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_certificates',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Certificate',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'select',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 10,
                    'is_user_defined' => true,
                    'user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'option' => ['values' => ['GIA', 'EGL USA', 'EGL Israel', 'IGI', 'AGS', 'HRD']],
                    'system' => false,
                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_carats',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Carat',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'text',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 30,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'system' => false,

                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_clarity',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Clarity',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'select',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 40,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'option' => ['values' => ['FL', 'IF', 'VVS1', 'VVS2', 'VS1', 'VS2', 'SI1', 'SI2', 'SI3', 'I1', 'I2', 'I3']],
                    'system' => false,


                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_color',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Color',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'select',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 50,
                    'is_user_defined' => 1,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'option' => ['values' => ['D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'FCY']],
                    'system' => false,


                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_cut',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Cut',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'select',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 60,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'option' => ['values' => ['Excellent', 'Ideal', 'Very Good', 'Good', 'Fair', 'Poor']],
                    'system' => false,


                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_polish',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Polish',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'select',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 70,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'option' => ['values' => ['Excellent', 'Very Good', 'Good', 'Fair', 'Poor']],
                    'system' => false,

                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_symmetry',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Symmetry',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'select',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 80,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'option' => ['values' => ['Excellent', 'Very Good', 'Good', 'Fair', 'Poor']],
                    'system' => false,

                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_table',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Table',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'text',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 90,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'system' => false,

                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_depth',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Depth',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'text',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 100,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'system' => false,

                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_measurements',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Measurements',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'text',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 110,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'system' => false,

                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_fluorescence',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Fluorescence',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'select',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 130,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'option' => ['values' => ['None', 'Faint', 'Medium', 'Strong', 'Very Strong']],
                    'system' => false,


                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_lab',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Lab',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'text',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 150,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'system' => false,

                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rapnet_diamond_certimg',
                [
                    'group' => 'Diamond Properties',
                    'type' => 'varchar',
                    'label' => 'Certificate Image',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'text',
                    'source' => null,
                    'required' => false,
                    'sort_order' => 160,
                    'is_user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true,
                    'visible_on_front' => true,
                    'system' => false,

                ]
            );
            $setup->endSetup();
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }

    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        try {
            if (version_compare($context->getVersion(), '1.1.0') < 0) {
                $this->removeRapnetAttribute($setup);
                $this->addRapnetAttribute($setup);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
