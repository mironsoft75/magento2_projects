<?php

/**
 * Created by PhpStorm.
 * User: pimcore
 * Date: 26/9/18
 * Time: 11:00 PM
 */




/**
 * Class UpgradeData
 * @package Pimcore\CartOption\Setup
 */


namespace Pimcore\CartOption\Setup;



use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Quote\Setup\QuoteSetupFactory;
use Psr\Log\LoggerInterface;

class UpgradeData  implements UpgradeDataInterface
{


    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * @var SalesSetup
     */
    private $salesSetupFactory;


    private $logger;
    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param QuoteSetupFactory $quoteSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        QuoteSetupFactory $quoteSetupFactory,
        LoggerInterface $logger,
        SalesSetupFactory $salesSetupFactory
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->logger = $logger;
        $this->salesSetupFactory = $salesSetupFactory;
    }


    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup ();

        if (version_compare ( $context->getVersion (), "2.0.1", "<" )) {
            $setup = $this->updateQuoteAndOrderTable ( $setup );

            $setup->endSetup ();
        }
    }


    public function updateQuoteAndOrderTable ($setup)
    {
        $setup->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /** @var QuoteSetup $quoteSetup */
        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);

        /** @var SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'fitment',
            [
                'type'                    => 'varchar',
                'label'                   => 'fitment',
                'input'                   => 'text',
                'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible'                 => true,
                'required'                => false,
                'user_defined'            => true,
                'default'                 => '',
                'searchable'              => true,
                'filterable'              => true,
                'comparable'              => false,
                'visible_on_front'        => false,
                'used_in_product_listing' => false,
                'unique'                  => false,

            ]
        );
        $this->logger->info("running upgrade");
        $attributeSetId = $eavSetup->getDefaultAttributeSetId('catalog_product');
        $eavSetup->addAttributeToSet(
            'catalog_product',
            $attributeSetId,
            'General',
            'fitment'
        );
        $attributeOptions = [
            'type'     => Table::TYPE_TEXT,
            'visible'  => true,
            'required' => false
        ];
        $quoteSetup->addAttribute('quote_item', 'fitment', $attributeOptions);
        $salesSetup->addAttribute('order_item', 'fitment', $attributeOptions);
        return $setup;
    }

}