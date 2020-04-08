<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 28/11/18
 * Time: 7:16 PM
 */

namespace Codilar\Rapnet\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('codilar_rapnet')
            )->addColumn(
                'rapnet_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Rapnet ID'
            )->addColumn(
                'diamond_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond Id'
            )->addColumn(
                'diamond_shape',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond Shape'
            )->addColumn(
                'diamond_lab',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond Lab'
            )->addColumn(
                'diamond_carats',
                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                255,
                ['nullable' => false],
                'Diamond Carats'
            )->addColumn(
                'diamond_clarity',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond Clarity'
            )->addColumn(
                'diamond_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond Color'
            )->addColumn(
                'diamond_cut',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond Cut'
            )->addColumn(
                'diamond_polish',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond Polish'
            )->addColumn(
                'diamond_symmetry',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond Symmetry'
            )->addColumn(
                'diamond_table_percent',
                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                255,
                ['nullable' => false],
                'Table Percent'
            )->addColumn(
                'diamond_depth_percent',
                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                255,
                ['nullable' => false],
                'Depth Percent'
            )->addColumn(
                'diamond_measurements',
                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                255,
                ['nullable' => false],
                'Diamond Measurements'
            )->addColumn(
                'diamond_fluoresence',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Fluoresnce Intensity'
            )->addColumn(
                'diamond_certificate_num',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Certificate Number'
            )->addColumn(
                'diamond_meas_width',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Meas Width'
            )->addColumn(
                'diamond_has_cert_file',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Has Certificate File'
            )->addColumn(
                'diamond_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond Price'
            )->addColumn(
                'diamond_stock_num',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Stock Number'
            )->addColumn(
                'diamond_has_image_file',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond has Image'
            )->addColumn(
                'diamond_image_file_url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Diamond Image File Url'
            )->addColumn(
                'codilar_rapnet_created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                255,
                ['nullable' => false],
                'Codilar Rapnet Created at'
            )->addColumn(
                'codilar_rapnet_updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                255,
                ['nullable' => false],
                'Codilar Rapnet Updated at'
            )->setComment(
                'Row Data Table'
            );
            $installer->getConnection()->createTable($table);
            $installer->endSetup();
        }
    }
}
