<?php
/**
 * Created by salman.
 * Date: 7/2/18
 * Time: 4:32 PM
 * Filename: InstallData.php
 */

namespace Pimcore\Aces\Setup;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product\AttributeSet\Options;
use Magento\Eav\Model\AttributeManagement;
use Magento\Eav\Model\AttributeSetManagement;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Eav\Model\Entity\TypeFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;
use Pimcore\Aces\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * Class InstallData
 * @package Magento\TestModule\Setup
 */
class InstallData implements InstallDataInterface
{

    CONST Categories = 'Categories.csv';
    CONST PARENT_CATEGORY = 2; //Default Category
    const SUB_MODELS = 'SubModels.csv';
    const YMM = 'YMM.csv';
    const CATEGORIES = 'Categories.csv';
    const DEFAULT_ATTRIBUTE_SET = 4; //Default Attribute Set

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
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
     * @param Options                     $attributeSets
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
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        try {
            //Create attribute sets based on websites
            $websites = $this->storeManager->getWebsites();
            $existingAttributeSets = $this->getAttributeSetsList();
            if (count($websites)) {
                $i = 1;
                foreach ($websites as $website) {
                    if (in_array($website->getName(), $existingAttributeSets)) {
                        continue;
                    }
                    $this->createAttributeSets($website->getName(), $i);
                    $i++;
                }
            }
            //Create Categories under Default Category
            $this->createDefaultCategories();
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->logger->critical($e);
        }
        $installer->endSetup();
    }

    /**
     *  Creates categories based on csv file
     */
    private function createDefaultCategories()
    {
        $data = $this->helper->readCsvFile(self::Categories);
        if (count($data)) {
            unset($data[0]);
            foreach ($data as $values) {
                $category = $this->categoryFactory->create();
                $category->setName($values[0]);
                $category->setParentId(self::PARENT_CATEGORY);
                $category->setIsActive(true);
                $category->setCustomAttributes([
                    'description' => $values[0],
                ]);
                $this->categoryRepository->save($category);
            }
        }
    }

    /**
     * @return array
     */
    private function getAttributeSetsList()
    {
        $attributeSets = $this->attributeSets->toOptionArray();
        $attributeSetsData = [];
        if (count($attributeSets)) {
            foreach ($attributeSets as $attributeSet) {
                $attributeSetsData[] = $attributeSet['label'];
            }
        }
        return $attributeSetsData;
    }

    /**
     * @param $attributeSetName
     * @param $sortOrder
     */
    private function createAttributeSets($attributeSetName, $sortOrder)
    {
        $entityTypeCode = 'catalog_product';
        $entityType = $this->eavTypeFactory->create()->loadByCode($entityTypeCode);
        $defaultSetId = $entityType->getDefaultAttributeSetId();
        $attributeSet = $this->attributeSetFactory->create();
        $data = [
            'attribute_set_name' => $attributeSetName,
            'entity_type_id' => $entityType->getId(),
            'sort_order' => $sortOrder,
            'skeleton_set' => self::DEFAULT_ATTRIBUTE_SET
        ];
        $attributeSet->setData($data);
        $this->attributeSetManagement->create($entityTypeCode, $attributeSet, $defaultSetId);
    }
}
