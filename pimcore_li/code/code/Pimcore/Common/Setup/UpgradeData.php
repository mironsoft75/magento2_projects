<?php

namespace Pimcore\Common\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Psr\Log\LoggerInterface;
/**
 * Class UpgradeData
 * @package Pimcore\Common\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /** @var \Magento\Framework\App\State **/
    private $state;

    /**
     * UpgradeData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param LoggerInterface $loggerInterface
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        LoggerInterface $loggerInterface,
        \Magento\Framework\App\State $state
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->logger = $loggerInterface;
        $this->state = $state;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML); // or \Magento\Framework\App\Area::AREA_ADMINHTML, depending on your needs
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $this->addSalesRankAttribute($setup);
        }
        $setup->endSetup();
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    private function addSalesRankAttribute(ModuleDataSetupInterface $setup){
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            "sales_rank",/* Custom Attribute Code */
            [
                'group' => 'Product Details',
                'type' => 'int',/* Data type in which formate your value save in database*/
                'backend' => '',
                'frontend' => '',
                'label' => 'Sales Rank', /* lable of your attribute*/
                'input' => 'text',
                'class' => '',
                'source' => '',
                /* Source of your select type custom attribute options*/
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                /*Scope of your attribute */
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => true,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_searchable_in_grid' => false,
                'is_filterable_in_grid' => false,
            ]
        );
    }

}