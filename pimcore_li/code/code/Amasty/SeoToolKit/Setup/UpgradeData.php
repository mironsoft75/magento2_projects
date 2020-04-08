<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_SeoToolKit
 */


namespace Amasty\SeoToolKit\Setup;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var ConfigInterface
     */
    private $resourceConfig;

    /**
     * UpgradeData constructor.
     * @param ConfigInterface $resourceConfig
     */
    public function __construct(ConfigInterface $resourceConfig)
    {
        $this->resourceConfig = $resourceConfig;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.4.0', '<')) {
            $this->movePagerSettings($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    private function movePagerSettings(ModuleDataSetupInterface $setup)
    {
        foreach (['meta_title', 'prev_next', 'meta_description'] as $field) {
            $this->updateConfigField($field);
        }
    }

    /**
     * @param string $field
     */
    private function updateConfigField($field)
    {
        $connection = $this->resourceConfig->getConnection();
        $tableName = $this->resourceConfig->getTable('core_config_data');
        $connection->update(
            $tableName,
            ['path' => "amseotoolkit/pager/" . $field],
            ["path = ?" => "amseotoolkit/general/" . $field]
        );
    }

}
