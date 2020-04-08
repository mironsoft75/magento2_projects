<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28/5/19
 * Time: 12:00 PM
 */

namespace Codilar\ProductImport\Helper;

use Codilar\MasterTables\Api\LocationNameRepositoryInterface;
use Codilar\MasterTables\Api\MetalBomRepositoryInterface;
use Codilar\MasterTables\Api\StoneBomRepositoryInterface;
use Codilar\MasterTables\Api\VariantNameRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product\Attribute\Repository as ProductAttribute;
use Magento\Catalog\Model\ProductRepository as ProductRepository;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Action as ProductAction;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ProductData;
use Magento\CatalogInventory\Api\StockStateInterface;
use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Registry as Registry;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Codilar\ProductImport\Helper\DeleteHelper;

/**
 * Class Data
 * @package Codilar\ProductImport\Helper
 */
class Data extends AbstractHelper
{
    const YES = "Yes";
    const NO = "No";
    const REGULAR = "Regular";
    const VISIBILITY = "visibility";
    const STONE_PCS = "stone_pcs";
    const DELETE_PRODUCT = "delete_product";
    const VARIANT_TYPE = "variant_type";
    const IS_IN_STOCK = "is_in_stock";
    const DEFAULT_STORE = "0";
    const INDIAN_STORE = "1";
    const USA_STORE = "2";
    const STONE_WEIGHT = "stone_weight";
    const BOM_VARIANT_NAME = "bom_variant_name";
    const XML_PATH_PRODUCT_DELETE_ON_CSV_IMPORT = 'product_delete/settings/product_delete_on_import';
    const XML_PATH_PRODUCT_UPDATE_ON_CSV_IMPORT = 'product_update/settings/product_update_on_import';
    const XML_PATH_COMPUTE_GST = 'compute_gst/settings/gst_calculate';
    const XML_PATH_GST_PERCENTAGE = 'compute_gst/settings/gst_percentage';
    const XML_PATH_COMPUTE_PRICE = 'product_compute_price/settings/compute_price';
    const XML_PATH_ASSIGN_CATEGORY = 'product_assign_category/settings/assign_category';

    /**
     * @var MetalBomRepositoryInterface
     */
    protected $_metalBomRepositoryInterface;
    /**
     * @var StoneBomRepositoryInterface
     */
    protected $_stoneBomRepositoryInterface;
    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var CollectionFactory
     */
    protected $_collecionFactory;
    /**
     * @var VariantNameRepositoryInterface
     */
    protected $_variantNameRepositoryInterface;
    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;
    /**
     * @var ProductAction
     */
    protected $_productAction;
    /**
     * @var ProductAttribute
     */
    protected $_productAttribute;
    /**
     * @var AttributeFactory
     */
    protected $_eavAttributeFactory;
    /**
     * @var AttributeOptionManagementInterface
     */
    protected $_attributeOptionManagement;
    /**
     * @var ProductCollection
     */
    protected $_productCollection;
    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var Registry
     */
    protected $_registry;
    /**
     * @var LocationNameRepositoryInterface
     */
    protected $_locationNameRepository;
    /**
     * @var StockStateInterface
     */
    protected $_stockStateInterface;
    /**
     * @var ProductData
     */
    protected $_productData;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var CategoryHelper
     */
    protected $categoryHelper;
    /**
     * @var \Codilar\ProductImport\Helper\DeleteHelper
     */
    protected $deleteHelper;

    /**
     * Data constructor.
     * @param MetalBomRepositoryInterface $metalBomRepository
     * @param StoneBomRepositoryInterface $stoneBomRepository
     * @param LoggerInterface $logger
     * @param CollectionFactory $collecionFactory
     * @param VariantNameRepositoryInterface $variantNameRepository
     * @param CategoryFactory $categoryFactory
     * @param ProductAction $productAction
     * @param ProductAttribute $attribute
     * @param AttributeFactory $attributeFactory
     * @param AttributeOptionManagementInterface $attributeOptionManagement
     * @param ProductCollection $productCollection
     * @param ProductRepository $catalogProductRepository
     * @param Registry $registry
     * @param LocationNameRepositoryInterface $locationNameRepository
     * @param StockStateInterface $stockStateInterface
     * @param ProductData $productData
     * @param StoreManagerInterface $storeManagerInterface
     * @param CategoryHelper $categoryHelper
     * @param \Codilar\ProductImport\Helper\DeleteHelper $deleteHelper
     * @param Context $context
     */
    public function __construct(
        MetalBomRepositoryInterface $metalBomRepository,
        StoneBomRepositoryInterface $stoneBomRepository,
        LoggerInterface $logger,
        CollectionFactory $collecionFactory,
        VariantNameRepositoryInterface $variantNameRepository,
        CategoryFactory $categoryFactory,
        ProductAction $productAction,
        ProductAttribute $attribute,
        AttributeFactory $attributeFactory,
        AttributeOptionManagementInterface $attributeOptionManagement,
        ProductCollection $productCollection,
        ProductRepository $catalogProductRepository,
        Registry $registry,
        LocationNameRepositoryInterface $locationNameRepository,
        StockStateInterface $stockStateInterface,
        ProductData $productData,
        StoreManagerInterface $storeManagerInterface,
        CategoryHelper $categoryHelper,
        DeleteHelper $deleteHelper,
        Context $context
    )
    {
        $this->_metalBomRepositoryInterface = $metalBomRepository;
        $this->_stoneBomRepositoryInterface = $stoneBomRepository;
        $this->_collecionFactory = $collecionFactory;
        $this->_variantNameRepositoryInterface = $variantNameRepository;
        $this->_categoryFactory = $categoryFactory;
        $this->_productAction = $productAction;
        $this->_productAttribute = $attribute;
        $this->_eavAttributeFactory = $attributeFactory;
        $this->_attributeOptionManagement = $attributeOptionManagement;
        $this->_productCollection = $productCollection;
        $this->productRepository = $catalogProductRepository;
        $this->_locationNameRepository = $locationNameRepository;
        $this->_registry = $registry;
        $this->_logger = $logger;
        $this->_stockStateInterface = $stockStateInterface;
        $this->_productData = $productData;
        $this->_storeManager = $storeManagerInterface;
        $this->categoryHelper = $categoryHelper;
        $this->deleteHelper = $deleteHelper;
        parent::__construct($context);
    }

    /**
     * Compute Product Price
     *
     * @return bool
     */
    public function computeProductPrice()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_COMPUTE_PRICE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Update Products
     *
     * @return bool
     */
    public function updateProducts()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_PRODUCT_UPDATE_ON_CSV_IMPORT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * Assign Categories
     *
     * @return bool
     */
    public function assignCategories()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ASSIGN_CATEGORY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Compute Gst
     *
     * @return bool
     */
    public function computeGst()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_COMPUTE_GST,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Gst Percentage
     *
     * @return mixed
     */
    public function gstPercentage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GST_PERCENTAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $category
     * @return mixed
     */
    public function getCategoryId($category)
    {
        /**
         * @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection
         */
        $collection = $this->_collecionFactory
            ->create()
            ->addAttributeToFilter('name', $category)
            ->setPageSize(1);
        if ($collection->getSize()) {
            return $collection->getFirstItem()->getId();
        }
    }

    /**
     * @param $category
     * @return mixed
     */
    public function getSubCategoryId($category)
    {
        $categoryId = $this->getCategoryId($category);
        return $this->getChildrenCategoryId($categoryId);
    }

    /**
     * @param $categoryId
     * @return mixed
     */
    public function getChildrenCategoryId($categoryId)
    {
        /**
         * @var \Magento\Catalog\Model\Category $category
         */
        $category = $this->_categoryFactory->create()->load($categoryId);
        return $category->getChildrenCategories()->getFirstItem()->getEntityId();
    }

    /**
     * @param $variantName
     * @return array
     */
    public function getCategoryIds($variantName)
    {
        try {
            $categoryIds = [];
            if (isset($variantName)) {
                $variantName = explode('-', $variantName);
                $variantDetails = $this->_variantNameRepositoryInterface->getVariantName($variantName[0]);
                if (isset($variantDetails)) {
                    $displayInUI = $variantDetails->getDisplayInUi();
                    $goldDiamondUncuts = explode('/', $variantDetails->getGoldDiamondUncut());
                    $category = $variantDetails->getCategory();
                    $subCategory = $variantDetails->getSubCategory();
                    $subSubCategory = $variantDetails->getSubSubCategory();
                    $rootCategoryId = $this->categoryHelper->getCategoryIdByName('Default Category');
                    if ($displayInUI == self::YES) {
                        foreach ($goldDiamondUncuts as $goldDiamondUncut) {
                            if (isset($goldDiamondUncut)) {
                                if (isset($category)) {
                                    if (isset($subCategory)) {
                                        if (isset($subSubCategory)) {
                                            $goldDiamondUncutId = $this->categoryHelper->getCategoryId($rootCategoryId, $goldDiamondUncut);
                                            $categoryId = $this->categoryHelper->getCategoryId($goldDiamondUncutId, $category);
                                            $subCategoryId = $this->categoryHelper->getCategoryId($categoryId, $subCategory);
                                            $subSubCategoryId = $this->categoryHelper->getCategoryId($subCategoryId, $subSubCategory);
                                            if (!in_array($goldDiamondUncutId, $categoryIds) && isset($goldDiamondUncutId)) {
                                                array_push($categoryIds, $goldDiamondUncutId);
                                            }
                                            if (!in_array($categoryId, $categoryIds) && isset($categoryId)) {
                                                array_push($categoryIds, $categoryId);
                                            }
                                            if (!in_array($subCategoryId, $categoryIds) && isset($subCategoryId)) {
                                                array_push($categoryIds, $subCategoryId);
                                            }
                                            if (!in_array($subSubCategoryId, $categoryIds) && isset($subSubCategoryId)) {
                                                array_push($categoryIds, $subSubCategoryId);
                                            }
                                        }
                                        $goldDiamondUncutId = $this->categoryHelper->getCategoryId($rootCategoryId, $goldDiamondUncut);
                                        $categoryId = $this->categoryHelper->getCategoryId($goldDiamondUncutId, $category);
                                        $subCategoryId = $this->categoryHelper->getCategoryId($categoryId, $subCategory);
                                        if (!in_array($goldDiamondUncutId, $categoryIds) && isset($goldDiamondUncutId)) {
                                            array_push($categoryIds, $goldDiamondUncutId);
                                        }
                                        if (!in_array($categoryId, $categoryIds) && isset($categoryId)) {
                                            array_push($categoryIds, $categoryId);
                                        }
                                        if (!in_array($subCategoryId, $categoryIds) && isset($subCategoryId)) {
                                            array_push($categoryIds, $subCategoryId);
                                        }
                                    }
                                    $goldDiamondUncutId = $this->categoryHelper->getCategoryId($rootCategoryId, $goldDiamondUncut);
                                    $categoryId = $this->categoryHelper->getCategoryId($goldDiamondUncutId, $category);
                                    if (!in_array($goldDiamondUncutId, $categoryIds) && isset($goldDiamondUncutId)) {
                                        array_push($categoryIds, $goldDiamondUncutId);
                                    }
                                    if (!in_array($categoryId, $categoryIds) && isset($categoryId)) {
                                        array_push($categoryIds, $categoryId);
                                    }
                                }
                                $goldDiamondUncutId = $this->categoryHelper->getCategoryId($rootCategoryId, $goldDiamondUncut);
                                if (!in_array($goldDiamondUncutId, $categoryIds) && isset($goldDiamondUncutId)) {
                                    array_push($categoryIds, $goldDiamondUncutId);
                                }

                            }

                        }
                        return $categoryIds;

                    }
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $bombVariant
     * @return array
     */
    public function removeBrackets($bombVariant)
    {
        $newVariants = [];
        foreach ($bombVariant as $vatiant) {
            array_push($newVariants, trim(preg_replace("/[{}]/", "", $vatiant)));
        }
        return $newVariants;
    }

    /**
     * @param $product
     * @return array
     */
    public function getBomVariantDeatails($product)
    {
        $bombDetails = [];
        try {
            $bombVariant = explode('},', $product->getBomVariantName());
            if (count(explode('{', $bombVariant[0])) >= 1) {
                $bombVariant[0] = explode('{', $bombVariant[0])[1];
                $bombVariants = $this->removeBrackets($bombVariant);
                foreach ($bombVariants as $individualBomb) {
                    $variants = explode(',', $individualBomb);
                    foreach ($variants as $variant) {
                        $res = explode(':', $variant);
                        $res[0] = trim($res[0], '"');
                        $res[1] = trim($res[1], '"');
                        $equalSeperated[$res[0]] = $res[1];
                    }
                    array_push($bombDetails, $equalSeperated);
                }
            }
            return $bombDetails;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $ratePerGram
     * @param $metalTotalWeight
     * @param $wastage
     * @param $labourValue
     * @return float|int
     */
    public function getMetalPrice($totalMetalRate, $metalTotalWeight, $wastage, $labourValue)
    {
        try {
            $labourValue = $labourValue * $metalTotalWeight;
            $wastageAmount = ($wastage * $totalMetalRate) / 100;
            return $totalMetalRate + $wastageAmount + $labourValue;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $product
     * @param $storeId
     * @return float|int
     */
    public function metalPriceCalculation($product, $storeId)
    {
        try {
            $wastage = $product->getWastage();
            $labourValue = $product->getLabourValue();
            $bomDetails = $this->getBomVariantDeatails($product);
            $totalMetalRate = 0;
            $metalTotalWeight = 0;
            if (isset($bomDetails)) {
                foreach ($bomDetails as $bomDetail) {
                    if (!$bomDetail[self::STONE_PCS]) {
                        $metalWeight = $bomDetail[self::STONE_WEIGHT];
                        if ($storeId === self::INDIAN_STORE) {
                            $ratePerGram = $this->_metalBomRepositoryInterface->getIndianRatePerGram($bomDetail[self::BOM_VARIANT_NAME]);
                        } elseif ($storeId === self::USA_STORE || $storeId === self::DEFAULT_STORE) {
                            $ratePerGram = $this->_metalBomRepositoryInterface->getUsaRatePerGram($bomDetail[selF::BOM_VARIANT_NAME]);
                        } else {
                            $ratePerGram = $this->_metalBomRepositoryInterface->getIndianRatePerGram($bomDetail[self::BOM_VARIANT_NAME]);
                        }
                        $totalMetalRate = $totalMetalRate + $metalWeight * $ratePerGram;
                        $metalTotalWeight = $metalTotalWeight + $metalWeight;

                    }
                }
                return $this->getMetalPrice($totalMetalRate, $metalTotalWeight, $wastage, $labourValue);
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $product
     * @param $storeId
     * @return float|int
     */
    public function stonePricecalculation($product, $storeId)
    {

        try {
            $bomDetails = $this->getBomVariantDeatails($product);
            $diamondPrice = 0;
            if (isset($bomDetails)) {
                foreach ($bomDetails as $bomDetail) {
                    if ($bomDetail[self::STONE_PCS]) {
                        $stoneTotalWeight = $bomDetail[self::STONE_WEIGHT];
                        if ($storeId === self::INDIAN_STORE) {
                            $ratePerStone = $this->_stoneBomRepositoryInterface->getIndianRatePerCarat($bomDetail[self::BOM_VARIANT_NAME]);
                        } elseif ($storeId === self::USA_STORE || $storeId === self::DEFAULT_STORE) {
                            $ratePerStone = $this->_stoneBomRepositoryInterface->getUsaRatePerCarat($bomDetail[self::BOM_VARIANT_NAME]);
                        } else {
                            $ratePerStone = $this->_stoneBomRepositoryInterface->getIndianRatePerCarat($bomDetail[self::BOM_VARIANT_NAME]);
                        }
                        $currentStone = $ratePerStone * $stoneTotalWeight;
                        $diamondPrice = $diamondPrice + $currentStone;
                    }
                }
                return $diamondPrice;
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $product
     * @return int
     */
    public function getDiamondWeight($product)
    {
        try {
            $bomDetails = $this->getBomVariantDeatails($product);
            $diamondWeight = 0;
            if (isset($bomDetails)) {
                foreach ($bomDetails as $bomDetail) {
                    if ($bomDetail[self::STONE_PCS]) {
                        $stoneTotalWeight = $bomDetail[self::STONE_WEIGHT];
                        $diamondWeight = $diamondWeight + $stoneTotalWeight;
                    }
                }
                return $diamondWeight;
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * Set delete_product as No for the first product of the collection
     * @param $store
     */
    public function updateProductAttributes($store)
    {
        try {
            $this->_storeManager->setCurrentStore($store->getId());
            $variantNames = $this->getVariantName();
            $updateAttributes[self::DELETE_PRODUCT] = $this->setAttributeValues(self::DELETE_PRODUCT, self::NO);
            foreach ($variantNames as $variantName) {
                /**
                 * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
                 */
                $collection = $this->_productCollection->create();
                $collection->addFieldToFilter(self::VARIANT_TYPE, $variantName);
                if ($collection->getSize()) {
                    $product = $collection->getFirstItem();
                    $this->_productAction->updateAttributes([$product->getId()], $updateAttributes, $this->_storeManager->getStore()->getId());
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function getVariantName()
    {
        try {
            $variantName = [];
            $variantTypes = $this->_variantNameRepositoryInterface->getCollection()->addFieldToSelect('variant_name');
            foreach ($variantTypes as $variantType) {
                array_push($variantName, $variantType['variant_name']);
            }
            return $variantName;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $attributeName
     * @param $attributeValue
     * @return mixed
     */
    public function setAttributeValues($attributeName, $attributeValue)
    {
        try {
            $options = $this->_productAttribute->get($attributeName)->getOptions();
            foreach ($options as $option) {
                if ($option->getValue() != '') {
                    if ($option->getLabel() == $attributeValue) {
                        return $option->getValue();
                    } else {
                        return $this->addAttributeOptions($attributeName, $attributeValue);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $attributeName
     * @param $attributeValue
     * @return string
     */
    public function addAttributeOptions($attributeName, $attributeValue)
    {
        try {
            /**
             * @var $magentoAttribute \Magento\Eav\Model\Entity\Attribute
             */
            $magentoAttribute = $this->_eavAttributeFactory->create()->loadByCode('catalog_product', $attributeName);

            $attributeCode = $magentoAttribute->getAttributeCode();
            $magentoAttributeOptions = $this->_attributeOptionManagement->getItems(
                'catalog_product',
                $attributeCode
            );
            $attributeOptions = [$attributeValue];
            $existingMagentoAttributeOptions = [];
            $newOptions = [];
            $counter = 0;
            foreach ($magentoAttributeOptions as $option) {
                if (!$option->getValue()) {
                    continue;
                }
                if ($option->getLabel() instanceof \Magento\Framework\Phrase) {
                    $label = $option->getText();
                } else {
                    $label = $option->getLabel();
                }

                if ($label == '') {
                    continue;
                }

                $existingMagentoAttributeOptions[] = $label;
                $newOptions['value'][$option->getValue()] = [$label, $label];
                $counter++;
            }

            foreach ($attributeOptions as $option) {
                if ($option == '') {
                    continue;
                }
                if (!in_array($option, $existingMagentoAttributeOptions)) {
                    $newOptions['value']['option_' . $counter] = [$option, $option];
                }

                $counter++;
            }

            if (count($newOptions)) {
                $magentoAttribute->setOption($newOptions)->save();
            }
            return $this->setNewAttributeValues($attributeName, $attributeValue);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $attributeName
     * @param $attributeValue
     * @return string
     */
    public function setNewAttributeValues($attributeName, $attributeValue)
    {
        try {
            $attributes = $this->_productAttribute->get($attributeName)->getOptions();
            foreach ($attributes as $attributeOption) {
                if ($attributeOption->getValue() != '' && $attributeOption->getLabel() == $attributeValue) {
                    return $attributeOption->getValue();
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * Delete all Products with Yes Flag
     */
    public function deleteProducts()
    {
        try {
            $idsToDelete = [];
            $value = $this->setAttributeValues(self::DELETE_PRODUCT, self::YES);
            $this->_registry->register('isSecureArea', true);
            /**
             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
             */
            $collection = $this->_productCollection->create();
            $collection->addFieldToFilter(self::DELETE_PRODUCT, ['eq' => $value]);
            if ($collection->getSize()) {
                foreach ($collection as $product) {
                    $productId = $product->getId();
                    $productSku = $product->getSku();
                    array_push($idsToDelete, $productSku);
//                    $this->deleteSingleProduct($productId);
                }
                $this->deleteHelper->productMassDelete($idsToDelete);
            }
            /**
             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $newCollection
             */
            $newCollection = $this->_productCollection->create();
            $newValue = $this->setAttributeValues(self::DELETE_PRODUCT, self::NO);
            $newCollection->addFieldToFilter(self::DELETE_PRODUCT, ['eq' => $newValue]);
            if ($newCollection->getSize()) {
                foreach ($newCollection as $product) {
                    $productId = $product->getId();
                    $productData = $this->productRepository->getById($productId, true, $this->_storeManager->getStore()->getId(), true);
                    /*
                     * If parent product need to Disable Uncomment the below line
                     */
//                    $productData->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
                    $productData->setStockData(
                        [
                            'use_config_manage_stock' => 1,
                            'manage_stock' => 1,
                            self::IS_IN_STOCK => 0,
                            'qty' => 0
                        ]
                    );
                    $productData->getQuantityAndStockStatus(
                        [
                            self::IS_IN_STOCK => 0,
                            'qty' => 0
                        ]
                    );
                    $this->productRepository->save($productData);
                }
            }

        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $productId
     */
    public function deleteSingleProduct($productId)
    {
        try {
            $product = $this->productRepository->getById($productId);
            $this->productRepository->delete($product);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $locationName
     * @param $depts
     * @return mixed|string
     */
    public function getCustomerFriendlyLocationName($locationName, $depts)
    {
        try {
            $customerLocationName = [];
            if (is_array($depts)) {
                $depts = array_unique($depts);
                foreach ($depts as $dept) {
                    $showAsInstock = $this->_locationNameRepository->getShowAsInStock($locationName, $dept);
                    if ($showAsInstock) {
                        $name = $this->_locationNameRepository->getCustomerFriendlyLocation($locationName, $dept);
                        array_push($customerLocationName, $name);
                    }
                }
                if (is_array($customerLocationName)) {
                    $customerLocationName = array_unique($customerLocationName);
                    $friendlyLocation = implode(',', $customerLocationName);
                }
            } else {
                $friendlyLocation = $this->_locationNameRepository->getCustomerFriendlyLocation($locationName, $depts);
            }
            return $friendlyLocation;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * GetLocationShowAsInStock
     *
     * @param $locationName
     * @param $depts
     * @return mixed|string
     */
    public function getLocationShowAsInStock($locationName, $depts)
    {
        try {
            $locationInstock = [];
            $depts = explode(',', $depts);
            if (is_array($depts)) {
                $depts = array_unique($depts);
                foreach ($depts as $dept) {
                    $showAsInstock = $this->_locationNameRepository->getShowAsInStock($locationName, $dept);
                    if ($showAsInstock) {
                        $showAsInstock=ucfirst($showAsInstock);
                        array_push($locationInstock, $showAsInstock);
                    }
                }

            } else {
                $showAsInstock = $this->_locationNameRepository->getShowAsInStock($locationName, $dept);
                if ($showAsInstock) {
                    $showAsInstock=ucfirst($showAsInstock);
                    array_push($locationInstock, $showAsInstock);
                }
            }
            return $locationInstock;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $productId
     */
    public function updateIndividualProductStockData($productId)
    {
        try {
            $productData = $this->productRepository->getById($productId, true, $this->_storeManager->getStore()->getId(), true);
            /**
             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $newProductCollection
             */
            $newProductCollection = $this->_productCollection->create();
            $newProductCollection->addFieldToFilter(self::VARIANT_TYPE, $productData->getVariantType());
            $productQty = $this->_stockStateInterface->getStockQty($productId, $productData->getStore()->getWebsiteId());
            $deleteProduct = $productData->getAttributeText(self::DELETE_PRODUCT);
            if ($deleteProduct == self::NO && $productQty <= 0) {
                if ($newProductCollection->getSize() > 1) {
                    $productData->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
                    $this->productRepository->save($productData);
                } else {
                    $productData->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
                    $productData->setStockData(
                        [
                            'use_config_manage_stock' => 1,
                            'manage_stock' => 1,
                            self::IS_IN_STOCK => 0,
                            'qty' => 0
                        ]
                    );
                    $productData->getQuantityAndStockStatus(
                        [
                            self::IS_IN_STOCK => 0,
                            'qty' => 0
                        ]
                    );
                    $this->productRepository->save($productData);
                }
            } else {
//                $this->deleteSingleProduct($productId);
                if ($newProductCollection->getSize() == 1) {
                    foreach ($newProductCollection as $product) {
                        $productData = $this->productRepository->getById($product->getId(), true, $this->_storeManager->getStore()->getId(), true);
                        $productData->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
                        $this->productRepository->save($productData);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * display Products
     * @param $store
     */
    public function displayProducts($store)
    {
        try {
            $idsToDelete = [];
            $this->_storeManager->setCurrentStore($store->getId());
            $variantNames = $this->getVariantName();
            $updateAttributes[self::DELETE_PRODUCT] = $this->setAttributeValues(self::DELETE_PRODUCT, self::NO);
            foreach ($variantNames as $variantName) {
                /**
                 * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
                 */
                $collection = $this->_productCollection->create();
                $collection->addFieldToFilter(self::VARIANT_TYPE, $variantName);
                if ($collection->getSize() > 1) {
                    foreach ($collection as $product) {
                        $productData = $this->productRepository->getById($product->getId(), true, null, true);
                        $productQty = $this->_stockStateInterface->getStockQty($product->getId(), $product->getStore()->getWebsiteId());
                        $stockStatus = $productData->getQuantityAndStockStatus();
                        if ($productData->getAttributeText('delete_product') == self::NO && $productData->getStatus() == 1
                            && $productQty <= 0 && $stockStatus[self::IS_IN_STOCK] == 0) {
                            $variantType = $product->getVariantType();
                            $productSku = $product->getSku();
                            array_push($idsToDelete, $productSku);
//                            $this->deleteSingleProduct($product->getId());
                            /**
                             * @var \Magento\Catalog\Model\ResourceModel\Product\Action $action
                             */
                            $action = $this->_productAction;
                            $updateAttributes[self::DELETE_PRODUCT] = $this->setAttributeValues(self::DELETE_PRODUCT, self::NO);
                            /**
                             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
                             */
                            $collection = $this->_productCollection->create();
                            $collection->addFieldToFilter(self::VARIANT_TYPE, $variantType);
                            if ($collection->getSize()) {
                                $product = $collection->getFirstItem();
                                $action->updateAttributes([$product->getId()], $updateAttributes, $this->_storeManager->getStore()->getId());
                            }
                        }
                    }
                    $this->deleteHelper->productMassDelete($idsToDelete);
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }
}