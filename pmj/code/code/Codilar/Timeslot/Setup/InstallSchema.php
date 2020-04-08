<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $timeslotTable = $installer->getTable('codilar_timeslot');
        if(!$installer->tableExists($timeslotTable)) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable($timeslotTable)
            )
            ->addColumn(
                'timeslot_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'day',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Day of the week'
            )->addColumn(
                'start_time',
                Table::TYPE_TEXT,
                10,
                ['nullable' => false],
                'Slot starting time'
            )->addColumn(
                'end_time',
                Table::TYPE_TEXT,
                10,
                ['nullable' => false],
                'Slot ending time'
            )->addColumn(
                'order_limit',
                Table::TYPE_SMALLINT,
                5,
                ['nullable' => false],
                'Time slot order limit'
                )
            ->addColumn(
                'is_active',
                Table::TYPE_SMALLINT,
                null,
                ['default' => 0, 'nullable' => false],
                'Slot Is Active'
            )->setComment(
                'Codilar Timeslot table'
            );
            $installer->getConnection()->createTable($table);
        }
    }
}