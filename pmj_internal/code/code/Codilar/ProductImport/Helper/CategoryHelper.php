<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/7/19
 * Time: 11:24 AM
 */

namespace Codilar\ProductImport\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Codilar\MasterTables\Api\VariantNameRepositoryInterface;
use Codilar\MasterTables\Model\VariantNameRepository;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;


/**
 * Class CategoryHelper
 * @package Codilar\ProductImport\Helper
 */
class CategoryHelper extends AbstractHelper
{
    const GOLD_DIAMOND_UNCUT = "gold_diamond_uncut";
    const GOLD_JEWELLERY = "Gold";
    const DIAMOND_JEWELLERY = "Diamond";
    const GOLD = "gold";
    const UNCUT = "Un-Cut";
    const DIAMOND = "diamond";
    /**
     * @var VariantNameRepositoryInterface
     */
    protected $_variantNameRepositoryInterface;
    /**
     * @var VariantNameRepository
     */
    protected $_variantNameRepository;
    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;
    /**
     * @var CategoryRepositoryInterface
     */
    protected $_categoryRepositoryInterface;
    /**
     * @var CollectionFactory
     */
    protected $_collecionFactory;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManagerInterface;
    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var Registry
     */
    protected $_registry;


    /**
     * CategoryHelper constructor.
     * @param VariantNameRepositoryInterface $variantNameRepositoryInterface
     * @param VariantNameRepository $variantNameRepository
     * @param CategoryFactory $categoryFactory
     * @param CategoryRepositoryInterface $categoryRepositoryInterface
     * @param CollectionFactory $collecionFactory
     * @param StoreManagerInterface $storeManager
     * @param Registry $registry
     * @param LoggerInterface $logger
     * @param Context $context
     */
    public function __construct
    (
        VariantNameRepositoryInterface $variantNameRepositoryInterface,
        VariantNameRepository $variantNameRepository,
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepositoryInterface,
        CollectionFactory $collecionFactory,
        StoreManagerInterface $storeManager,
        Registry $registry,
        LoggerInterface $logger,
        Context $context
    )
    {
        $this->_variantNameRepositoryInterface = $variantNameRepositoryInterface;
        $this->_variantNameRepository = $variantNameRepository;
        $this->_categoryFactory = $categoryFactory;
        $this->_collecionFactory = $collecionFactory;
        $this->_categoryRepositoryInterface = $categoryRepositoryInterface;
        $this->_storeManagerInterface = $storeManager;
        $this->_registry = $registry;
        $this->_logger = $logger;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function getRootCategoryId()
    {
        try {
            $store = $this->_storeManagerInterface->getStore()->getId();
            return $this->_storeManagerInterface->getStore($store)->getRootCategoryId();
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $variantNameCollection
     * @return array
     */
    public function getGoldDiamondUncuts($variantNameCollection)
    {
        try {
            $goldDiamondUncuts = [];
            foreach ($variantNameCollection as $variantName) {
                if ($variantName->getGoldDiamondUncut()) {
                    $goldDiamondUncutArray = explode('/', $variantName->getGoldDiamondUncut());
                    foreach ($goldDiamondUncutArray as $goldDiamondUncutIndusual) {
                        array_push($goldDiamondUncuts, $goldDiamondUncutIndusual);
                    }
                }
            }
            return $goldDiamondUncuts;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }


    /**
     * @param $category
     * @return array
     */
    public function getCategories($category)
    {
        try {
            if ($category->getSize()) {
                unset($categories);
                $categories = [];
                foreach ($category as $categoryData) {
                    if ($categoryData->getCategory()) {
                        array_push($categories, $categoryData->getCategory());
                    }
                }
            }
            return $categories;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $subCategory
     * @return array
     */
    public function getSubCategories($subCategory)
    {
        try {
            if ($subCategory->getSize()) {
                unset($subCategories);
                $subCategories = [];
                foreach ($subCategory as $subCategoryData) {
                    if ($subCategoryData->getSubCategory()) {
                        array_push($subCategories, $subCategoryData->getSubCategory());
                    }
                }
            }
            return $subCategories;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $subSubCategory
     * @return array
     */
    public function getSubSubCategories($subSubCategory)
    {
        try {
            if ($subSubCategory->getSize()) {
                unset($subSubCategories);
                $subSubCategories = [];
                foreach ($subSubCategory as $subSubCategoryData) {
                    if ($subSubCategoryData->getSubSubCategory()) {
                        array_push($subSubCategories, $subSubCategoryData->getSubSubCategory());
                    }
                }
            }
            return $subSubCategories;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $subSubCategories
     * @param $subCategoryId
     */
    public function createSubSubCategory($subSubCategories, $subCategoryId)
    {
        try {
            if (!empty($subSubCategories)) {
                $subSubCategories = array_unique($subSubCategories);
                foreach ($subSubCategories as $subSubCategory) {
                    $this->createNewCategory($subSubCategory, $subCategoryId);
                }

            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }


    /**
     * @param $subCategories
     * @param $categoryId
     * @param $goldDiamondUncutData
     * @param $category
     */
    public function createSubCategory($subCategories, $categoryId, $goldDiamondUncutData, $category)
    {
        try {
            foreach ($subCategories as $subCategory) {
                $subCategoryId = $this->createNewCategory($subCategory, $categoryId);
                $subSubCategory = $this->_variantNameRepository->getCollection()
                    ->addFieldToFilter(self::GOLD_DIAMOND_UNCUT, array('like' => '%' . $goldDiamondUncutData . '%'))
                    ->addFieldToFilter("category", $category)
                    ->addFieldToFilter('sub_category', $subCategory);
                $subSubCategories = $this->getSubSubCategories($subSubCategory);
                $this->createSubSubCategory($subSubCategories, $subCategoryId);

            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $categories
     * @param $goldDiamondUncutId
     * @param $goldDiamondUncutData
     */
    public function createCategory($categories, $goldDiamondUncutId, $goldDiamondUncutData)
    {
        try {
            foreach ($categories as $category) {
                $categoryId = $this->createNewCategory($category, $goldDiamondUncutId);
                $subCategory = $this->_variantNameRepository->getCollection()
                    ->addFieldToFilter(self::GOLD_DIAMOND_UNCUT, array('like' => '%' . $goldDiamondUncutData . '%'))
                    ->addFieldToFilter("category", $category);
                $subCategories = $this->getSubCategories($subCategory);
                if (!empty($subCategories)) {
                    $subCategories = array_unique($subCategories);
                    $this->createSubCategory($subCategories, $categoryId, $goldDiamondUncutData, $category);
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $parentCategoryId
     * @param $childCategoryName
     * @return false|int|string
     */
    public function getChildCategoryIdByName($parentCategoryId, $childCategoryName)
    {
        try {

            $childCategoryIds = [];
            /**
             * @var \Magento\Catalog\Model\Category $category
             */
            $category = $this->_categoryFactory->create()->load($parentCategoryId);
            if ($category->hasChildren()) {
                $subcategories = explode(',', $category->getChildren());
                foreach ($subcategories as $category) {
                    /**
                     * @var \Magento\Catalog\Model\Category $subcategory
                     */
                    $subcategory = $this->_categoryFactory->create()->load($category);
                    $childCategoryIds[$subcategory->getId()] = $subcategory->getName();
                }
            }
            $childCategoryName = $this->getCategoryName($childCategoryName);
            return array_search($childCategoryName, $childCategoryIds);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * Get Category Name
     *
     * @param $childCategoryName
     * @return string
     */
    public function getCategoryName($childCategoryName)
    {
        switch ($childCategoryName) {
            case self::GOLD:
                $categoryName = "Gold Jewellery";
                break;
            case self::UNCUT:
                $categoryName = "Un-Cut Jewellery";
                break;
            case self::DIAMOND:
                $categoryName = "Diamond Jewellery";
                break;
            case self::GOLD_JEWELLERY:
                $categoryName = "Gold Jewellery";
                break;
            case self::DIAMOND_JEWELLERY:
                $categoryName = "Diamond Jewellery";
                break;
            default:
                $categoryName = ucfirst($childCategoryName);
                break;
        }
        return $categoryName;
    }

    /**
     * @param $categoryId
     * @return \Magento\Catalog\Api\Data\CategoryInterface
     */
    public function getCategoryDetailsById($categoryId)
    {
        try {
            return $this->_categoryRepositoryInterface->get($categoryId);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * create Category based on the variant table
     */
    public function createCategoryForVariantCollection()
    {
        try {
            $rootCategoryId = $this->getRootCategoryId();
            $variantNameCollection = $this->_variantNameRepositoryInterface->getCollection();
            $goldDiamondUncuts = $this->getGoldDiamondUncuts($variantNameCollection);
            if (!empty($goldDiamondUncuts)) {
                $goldDiamondUncutDetails = array_unique($goldDiamondUncuts);
                rsort($goldDiamondUncutDetails);
                foreach ($goldDiamondUncutDetails as $goldDiamondUncutData) {
                    $goldDiamondUncutId = $this->getCategoryIdByName($goldDiamondUncutData);
                    if ($goldDiamondUncutId) {
                        $isChild = $this->checkChildCategoryExist($rootCategoryId, $goldDiamondUncutId);
                        if (!isset($isChild)) {
                            $isChild = NULL;
                        }
                    } else {
                        $isChild = NULL;
                    }
                    if (is_null($goldDiamondUncutId)) {
                        $goldDiamondUncutId = $this->createNewCategory($goldDiamondUncutData, $rootCategoryId);
                        $category = $this->_variantNameRepository->getCollection()
                            ->addFieldToFilter(self::GOLD_DIAMOND_UNCUT, array('like' => '%' . $goldDiamondUncutData . '%'));
                        $categories = $this->getCategories($category);
                        if (!empty($categories)) {
                            $categories = array_unique($categories);
                            $this->createCategory($categories, $goldDiamondUncutId, $goldDiamondUncutData);
                        }
                    } else {
                        if (is_null($isChild)) {
                            $goldDiamondUncutId = $this->createNewCategory($goldDiamondUncutData, $rootCategoryId);
                            $category = $this->_variantNameRepository->getCollection()
                                ->addFieldToFilter(self::GOLD_DIAMOND_UNCUT, array('like' => '%' . $goldDiamondUncutData . '%'));
                            $categories = $this->getCategories($category);
                            if (!empty($categories)) {
                                $categories = array_unique($categories);
                                $this->createCategory($categories, $goldDiamondUncutId, $goldDiamondUncutData);
                            }
                        }
                    }
                    unset($goldDiamondUncutId);
                    unset($isChild);
                }
            }
            $regularId = $this->getChildCategoryIdByName($rootCategoryId, "Regular");
            if ($regularId) {
                $regular = $this->getCategoryDetailsById($regularId);
                $this->_registry->register("isSecureArea", true);
                $this->_categoryRepositoryInterface->delete($regular);
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

    /**
     * @param $parentCategoryId
     * @param $goldDiamondUncutDetails
     * @return array
     */
    public function getChildCategoryIds($parentCategoryId, $goldDiamondUncutDetails)
    {
        try {
            $childCategoryIds = [];
            /**
             * @var \Magento\Catalog\Model\Category $category
             */
            $category = $this->_categoryFactory->create()->load($parentCategoryId);
            if ($category->hasChildren()) {
                $subcategories = explode(',', $category->getChildren());
                foreach ($subcategories as $category) {
                    /**
                     * @var \Magento\Catalog\Model\Category $subcategory
                     */
                    $subcategory = $this->_categoryFactory->create()->load($category);
                    $subcategoryName = $subcategory->getName();
                    $subcategoryKey = array_search($subcategoryName, $goldDiamondUncutDetails);
                    if ($subcategoryKey) {
                        array_push($childCategoryIds, $subcategory->getId());
                    }
                }
            }
            return $childCategoryIds;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $categoryName
     * @param $parentCategoryId
     * @return mixed
     */
    public function createNewCategory($categoryName, $parentCategoryId)
    {
        try {
            $name = ucfirst($categoryName);
            /**
             * @var $newCategory \Magento\Catalog\Model\Category
             */
            $newCategory = $this->_categoryFactory->create();
            $newCategory->setName($name);
            $newCategory->setIsActive(true);
            $newCategory->setStoreId(0);
            $newCategory->setParentId($parentCategoryId);
            $this->_categoryRepositoryInterface->save($newCategory);
            return $newCategory->getId();
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

    /**
     * @param $category
     * @return mixed
     */
    public function getCategoryIdByName($category)
    {
        try {
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
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

    /**
     * @param $parentCategoryId
     * @param $childCategoryId
     * @return bool
     */
    public function checkChildCategoryExist($parentCategoryId, $childCategoryId)
    {
        try {
            $childCategoryIds = [];
            /**
             * @var \Magento\Catalog\Model\Category $category
             */
            $category = $this->_categoryFactory->create()->load($parentCategoryId);
            if ($category->hasChildren()) {
                $subcategories = explode(',', $category->getChildren());
                foreach ($subcategories as $category) {
                    /**
                     * @var \Magento\Catalog\Model\Category $subcategory
                     */
                    $subcategory = $this->_categoryFactory->create()->load($category);
                    array_push($childCategoryIds, $subcategory->getId());
                }
            }
            return array_search($childCategoryId, $childCategoryIds);

        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $rootCategoryId
     * @param $goldDiamondUncut
     * @return false|int|mixed|string
     */
    public function getCategoryId($rootCategoryId, $goldDiamondUncut)
    {
        try {
            $goldDiamondUncutId = $this->getChildCategoryIdByName($rootCategoryId, $goldDiamondUncut);

//            if (empty($goldDiamondUncutId)) {
//                $goldDiamondUncutId = $this->createNewCategory($goldDiamondUncut, $rootCategoryId);
//            }
            return $goldDiamondUncutId;

        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }

    }
}