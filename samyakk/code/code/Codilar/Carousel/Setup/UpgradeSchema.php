<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/19
 * Time: 1:18 PM
 */

namespace Codilar\Carousel\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Codilar\Carousel\Model\ResourceModel\Carousel\Item as CarouselItemResourceModel;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(),'1.0.1') < 0) {
            $setup->getConnection()->changeColumn(
                $setup->getTable(CarouselItemResourceModel::TABLE_NAME),
                'content',
                'content',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '2M'
                ]
            );
        }
        $setup->endSetup(); 
    }
}