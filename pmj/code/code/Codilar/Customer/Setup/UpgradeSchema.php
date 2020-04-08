<?php
namespace Codilar\Customer\Setup;
/**
 * Class UpgradeSchema
 * @package Codilar\Customer\Setup
 */
class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        // dropping codilar_customer_otp table
        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $connection = $installer->getConnection();
            $connection->dropTable($connection->getTableName('codilar_customer_otp'));
        }
		//END   table setup
		$installer->endSetup();
    }
}
