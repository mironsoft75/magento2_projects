<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\HomePage\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

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
        $this->addDesignIdentifierColumnToHomepageElements($setup);
        $setup->endSetup();
    }

    protected function addDesignIdentifierColumnToHomepageElements(SchemaSetupInterface $setup)
    {
        $column = "design_identifier";
        $definition = [
            'type' => Table::TYPE_TEXT,
            'size' => null,
            'nullable' => false,
            'comment' => 'Design Identifier'
        ];
        $tables = [
            "magestore_bannerslider_slider",
            "codilar_carousel",
            "codilar_offers",
            "cms_block"
        ];
        foreach ($tables as $table) {
            $this->addTableColumnIfNotExists($setup, $table, $column, $definition);
        }
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param string $table
     * @param string $columnName
     * @param array $data
     */
    protected function addTableColumnIfNotExists(SchemaSetupInterface $setup, $table, $columnName, $data = []) {
        $table = $setup->getTable($table);
        if (!$setup->getConnection()->tableColumnExists($table, $columnName)) {
            $setup->getConnection()->addColumn($table, $columnName, $data);
        }
    }
}