<?php
/**
 * Created by salman.
 * Date: 7/2/18
 * Time: 5:05 PM
 * Filename: UpgradeData.php
 */

namespace Pimcore\Aces\Setup;

use Magento\Catalog\Model\Config as CtalogConfig;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Psr\Log\LoggerInterface;

/**
 * Class UpgradeData
 * @package Magento\TestModule\Setup
 */
class UpgradeData implements UpgradeDataInterface
{

    const ATTR_FILE = 'ProductCategoryAttributes.csv';
    const ATTR_GROUP_YEAR = 'ACES Years List';
    const ATTR_GROUP_MAKE = 'ACES Make Name';
    const ATTR_GROUP_MODEL = 'ACES Model Name';
    const ATTR_GROUP_BASE_VEHICLE = 'ACES Base Vehicle Id';

    private $ymmMultiSelectAttributes = [
        'year_id' => [
            'group' => self::ATTR_GROUP_YEAR,
        ],
        'make_name' => [
            'group' => self::ATTR_GROUP_MAKE,
        ],
        'model_name' => [
            'group' => self::ATTR_GROUP_MODEL,
        ],
        'base_vehicle_id' => [
            'group' => self::ATTR_GROUP_BASE_VEHICLE,
        ]
    ];


    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;


    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var CtalogConfig
     */
    private $config;

    /**
     * UpgradeData constructor.
     * @param EavSetupFactory      $eavSetupFactory
     * @param CategorySetupFactory $categorySetupFactory
     * @param LoggerInterface      $logger
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory,
        LoggerInterface $logger,
        CtalogConfig $config
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        // TODO: Implement upgrade() method.
        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            //change base_vehicle_id code
            $this->changeAttributeCode($installer, 'base_vehicle_id', 'base_vehivle_id');
        }

        $installer->endSetup();
    }

    private function changeAttributeCode($installer, $newCode, $oldCode)
    {
        $sql = "UPDATE `eav_attribute` SET `attribute_code`='$newCode' WHERE `attribute_code` = '$oldCode'";
        $installer->run($sql);
    }

}
