<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @codeCoverageIgnore
 */
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
        if (version_compare($context->getVersion(),'1.1.0') < 0) {
            $installer->getConnection()->addColumn(
                $installer->getTable('magestore_bannerslider_banner'),
                'image_text',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'after' => "image",
                    'comment' => 'Desktop Image text in Banner Slider'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('magestore_bannerslider_banner'),
                'mobile_image',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'after' => 'image_text',
                    'comment' => 'Mobile Image in Banner Slider'
                ]
            );

            $installer->getConnection()->addColumn(
                $installer->getTable('magestore_bannerslider_banner'),
                'mobile_image_text',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'after' => 'mobile_image',
                    'comment' => 'Mobile Image text in Banner Slider'
                ]
            );

            $installer->getConnection()->addColumn(
                $installer->getTable('magestore_bannerslider_banner'),
                'tablet_image',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'after' => 'mobile_image_text',
                    'comment' => 'Tablet Image in Banner Slider'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('magestore_bannerslider_banner'),
                'tablet_image_text',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'after' => 'tablet_image',
                    'comment' => 'Tablet Image text in Banner Slider'
                ]
            );


        }
        try {
            if (version_compare($context->getVersion(),'1.1.1') < 0) {
                $installer->getConnection()->addColumn(
                    $installer->getTable('magestore_bannerslider_slider'),
                    'sort_order',
                    [
                        'type' => Table::TYPE_FLOAT,
                        'length' => "10,3",
                        'nullable' => true,
                        'comment' => 'Sort Order'
                    ]
                );
                $installer->getConnection()->addColumn(
                    $installer->getTable('magestore_bannerslider_slider'),
                    'show_in_homepage',
                    [
                        'type' => Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => true,
                        'after' => 'slider_id',
                        'comment' => 'Flag for showing slider in homepage'
                    ]
                );
            }
        } catch (\Exception $e) {
            echo $e->getMessage();die;
        }


        $installer->endSetup();
    }
}
