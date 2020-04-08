<?php

namespace Pimcore\Aces\Setup;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product\AttributeSet\Options;
use Magento\Eav\Model\AttributeManagement;
use Magento\Eav\Model\AttributeSetManagement;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Eav\Model\Entity\TypeFactory;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Store\Model\StoreManagerInterface;
use Pimcore\Aces\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * Class InstallSchema
 * @package Pimcore\YmmImport\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    const SUB_MODELS = 'SubModels.csv';
    const YMM = 'YMM.csv';
    /**
     * @var TypeFactory
     */
    private $eavTypeFactory;
    /**
     * @var SetFactory
     */
    private $attributeSetFactory;
    /**
     * @var AttributeSetManagement
     */
    private $attributeSetManagement;
    /**
     * @var AttributeManagement
     */
    private $attributeManagement;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var Options
     */
    private $attributeSets;

    /**
     * InstallSchema constructor.
     * @param TypeFactory                 $eavTypeFactory
     * @param SetFactory                  $attributeSetFactory
     * @param AttributeSetManagement      $attributeSetManagement
     * @param AttributeManagement         $attributeManagement
     * @param StoreManagerInterface       $storeManager
     * @param LoggerInterface             $logger
     * @param Data                        $helper
     * @param CategoryFactory             $categoryFactory
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        TypeFactory $eavTypeFactory,
        SetFactory $attributeSetFactory,
        AttributeSetManagement $attributeSetManagement,
        AttributeManagement $attributeManagement,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        Data $helper,
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        Options $attributeSets
    )
    {

        $this->eavTypeFactory = $eavTypeFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeSetManagement = $attributeSetManagement;
        $this->attributeManagement = $attributeManagement;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->helper = $helper;
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->attributeSets = $attributeSets;
    }


    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        try {
            /* Create table pimcore_aces_ymm */
            $installer->getConnection()->dropTable($installer->getTable('pimcore_aces_ymm'));
            $table = $installer->getConnection()->newTable(
                $installer->getTable('pimcore_aces_ymm')
            )->addColumn(
                'base_vehicle_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Base Vehicle ID'
            )->addColumn(
                'year_id',
                Table::TYPE_INTEGER,
                4,
                ['nullable' => false],
                'Year'
            )->addColumn(
                'make_name',
                Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'Make Name'
            )->addColumn(
                'model_name',
                Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'Model Name'
            )->addColumn(
                'vehicle_type_name',
                Table::TYPE_TEXT,
                56,
                ['nullable' => false],
                'Vehicle Type Name'
            )->setComment(
                'pimcore aces ymm'
            );
            $installer->getConnection()->createTable($table);
            $this->insertDefaultYmmData($installer);

            /* Create table pimcore_aces_submodel */
            $installer->getConnection()->dropTable($installer->getTable('pimcore_aces_submodel'));
            $table = $installer->getConnection()->newTable(
                $installer->getTable('pimcore_aces_submodel')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                'base_vehicle_id',
                Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Base Vehicle ID'
            )->addColumn(
                'model_name',
                Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'Model Name'
            )->addColumn(
                'sub_model_name',
                Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'Sub Model Name'
            )->addForeignKey(
                $installer->getFkName('pimcore_aces_submodel', 'base_vehicle_id', 'pimcore_aces_ymm', 'base_vehicle_id'),
                'base_vehicle_id',
                $installer->getTable('pimcore_aces_ymm'),
                'base_vehicle_id',
                Table::ACTION_CASCADE
            )->setComment(
                'pimcore aces submodel'
            );

            $installer->getConnection()->createTable($table);
            $this->insertDefaultSubmodelData($installer);

        } catch (\Exception $e) {
            print_r($e->getMessage());
            $this->logger->critical($e);
        }
        $installer->endSetup();
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    private function insertDefaultSubmodelData($installer)
    {
        $data = $this->makeDataArray(self::SUB_MODELS);
        try {
            $installer->getConnection()->insertMultiple('pimcore_aces_submodel', $data);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    private function insertDefaultYmmData($installer)
    {
        $data = $this->makeDataArray(self::YMM);
        try {
            $installer->getConnection()->insertMultiple('pimcore_aces_ymm', $data);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }

    /**
     * @param string $file
     * @return array|string
     */
    private function makeDataArray($file)
    {
        $data = $this->helper->readCsvFile($file);
        $columns = $data[0];
        unset($data[0]);
        $csvData = [];
        foreach ($data as $serviceData) {
            $csvData[] = array_combine($columns, $serviceData);
        }
        return $csvData;
    }

}