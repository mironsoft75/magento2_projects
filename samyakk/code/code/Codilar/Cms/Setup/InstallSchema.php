<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Cms\Setup;

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
        $setup->startSetup();
        $tableName = $setup->getConnection()->getTableName("cms_block");
        $this->addTableColumnIfNotExists($setup, $tableName, "show_in_homepage", [
            'type' => Table::TYPE_INTEGER,
            'size' => 5,
            'default' => 1,
            'nullable' => false,
            'after' => 'block_id',
            'comment' => 'Show Block in Homepage'
        ]);
        $this->addTableColumnIfNotExists($setup, $tableName, "sort_order", [
            'type' => Table::TYPE_INTEGER,
            'size' => 5,
            'default' => 1,
            'nullable' => false,
            'after' => 'is_active',
            'comment' => 'Show Block in Homepage'
        ]);
        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param string $table
     * @param string $columnName
     * @param array $data
     */
    protected function addTableColumnIfNotExists(SchemaSetupInterface $setup, string $table, string $columnName, $data = []) {
        if (!$setup->getConnection()->tableColumnExists($table, $columnName)) {
            $setup->getConnection()->addColumn($table, $columnName, $data);
        }
    }
}
