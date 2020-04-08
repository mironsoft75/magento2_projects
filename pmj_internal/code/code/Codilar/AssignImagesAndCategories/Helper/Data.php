<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/10/19
 * Time: 4:25 PM
 */

namespace Codilar\AssignImagesAndCategories\Helper;

use Codilar\AssignImagesAndCategories\Api\ImagesAndCategoriesRepositoryInterface;
use Codilar\AssignImagesAndCategories\Model\Storage\DbStorage;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Model\ResourceModel\Product\Action as ProductAction;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogImportExport\Model\Import\Proxy\Product\ResourceModelFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Data
 *
 * @package Codilar\AssignImagesAndCategories\Helper
 */
class Data extends AbstractHelper
{
    const IMAGE_CUSTOM_URLS = 'image_custom_urls';
    const VIDEO_THUMBNAIL_URL = 'video_thumbnail_url';
    const VIDEO_URL = "video_url";
    const CUSTOMER_FRIENDLY_LOCATION_NAME = "customer_friendly_location_name";
    const CATEGORY_IDS = "category_ids";
    const VISIBILITY = "visibility";
    /**
     * ImagesAndCategoriesRepositoryInterface
     *
     * @var ImagesAndCategoriesRepositoryInterface
     */
    protected $imagesAndCategoriesRepository;
    /**
     * Collection Factory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * DbStorage
     *
     * @var DbStorage
     */
    protected $dbStorage;
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * ProductAction
     *
     * @var ProductAction
     */
    protected $productAction;
    /**
     * StoreManagerInterface
     *
     * @var StoreManagerInterface
     */
    private $_storeManager;
    /**
     * CategoryLinkManagementInterface
     *
     * @var CategoryLinkManagementInterface
     */
    protected $categoryLinkManagementInterface;
    /**
     * @var \Magento\CatalogImportExport\Model\Import\Proxy\Product\ResourceModelFactory
     */
    protected $_resourceFactory;
    /**
     * @var ResourceConnection
     */
    protected $resource;
    /**
     * DB connection
     *
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $_connection;

    /**
     * Data constructor.
     *
     * @param ImagesAndCategoriesRepositoryInterface $imagesAndCategoriesRepository
     * @param CollectionFactory $collectionFactory
     * @param DbStorage $dbStorage
     * @param ProductAction $productAction
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param ResourceModelFactory $resourceFactory
     * @param CategoryLinkManagementInterface $categoryLinkManagementInterface
     * @param ResourceConnection $resource
     * @param Context $context
     */
    public function __construct(
        ImagesAndCategoriesRepositoryInterface $imagesAndCategoriesRepository,
        CollectionFactory $collectionFactory,
        DbStorage $dbStorage,
        ProductAction $productAction,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        ResourceModelFactory $resourceFactory,
        CategoryLinkManagementInterface $categoryLinkManagementInterface,
        ResourceConnection $resource,
        Context $context
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->dbStorage = $dbStorage;
        $this->imagesAndCategoriesRepository = $imagesAndCategoriesRepository;
        $this->productAction = $productAction;
        $this->_logger = $logger;
        $this->_storeManager = $storeManager;
        $this->_resourceFactory = $resourceFactory;
        $this->categoryLinkManagementInterface = $categoryLinkManagementInterface;
        $this->_connection = $resource->getConnection();
        parent::__construct($context);
    }

    /**
     * TruncateTable
     */
    public function truncateTable()
    {
        try {
            $imagesAndCategoriesCollection = $this->imagesAndCategoriesRepository
                ->getCollection();
            /** @var \Magento\Framework\DB\Adapter\AdapterInterface $connection */
            $connection = $imagesAndCategoriesCollection->getConnection();
            $tableName = $imagesAndCategoriesCollection->getMainTable();
            $connection->truncateTable($tableName);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * InsertData
     */
    public function insertData()
    {
        try {
            $productAttributes = [
                self::IMAGE_CUSTOM_URLS,
                self::VIDEO_THUMBNAIL_URL,
                self::VIDEO_URL,
                self::CUSTOMER_FRIENDLY_LOCATION_NAME,
                self::CATEGORY_IDS,
                self::VISIBILITY
            ];
            $this->truncateTable();
            /**
             * Product Collection
             *
             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $products
             */
            $products = $this->collectionFactory->create();
            $products->addAttributeToSelect($productAttributes);
            if ($products->getSize()) {
                foreach ($products as $productInfo) {
                    if (is_array($productInfo->getCustomerFriendlyLocationName())) {
                        $customerFriendlyLocation = implode(
                            ",",
                            $productInfo->getCustomerFriendlyLocationName()
                        );
                    } else {
                        $customerFriendlyLocation = $productInfo
                            ->getCustomerFriendlyLocationName();
                    }
                    $bulkInsert[] = [
                        'product_sku' => $productInfo->getSku(),
                        'images' => $productInfo->getImageCustomUrls(),
                        'video_thumbnail' => $productInfo->getVideoThumbnailUrl(),
                        'videos' => $productInfo->getVideoUrl(),
                        'categories' => implode(",", $productInfo->getCategoryIds()),
                        'customer_friendly_location_name' => $customerFriendlyLocation,
                        'product_visibility' => $productInfo->getVisibility()
                    ];
                }
            }
            if ($bulkInsert) {
                $this->dbStorage->insertMultiple($bulkInsert);
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * DB connection getter.
     *
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * AssignCategories
     *
     * @param $productId
     * @param $categories
     */
    public function assignCategories($productId, $categories)
    {
        try {
            $tableName = $this->_resourceFactory->create()
                ->getProductCategoryTable();
            $categoriesIn = [];
            foreach ($categories as $categoryId) {
                $categoriesIn[] = ['product_id' => $productId,
                    'category_id' => $categoryId, 'position' => 0];
            }
            if ($categoriesIn) {
                $this->_connection->insertOnDuplicate(
                    $tableName,
                    $categoriesIn,
                    ['product_id', 'category_id']
                );
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * UpdateProductValues
     */
    public function updateProductValues()
    {
        try {
            $productAttributes = [
                self::IMAGE_CUSTOM_URLS,
                self::VIDEO_THUMBNAIL_URL,
                self::VIDEO_URL,
                self::CUSTOMER_FRIENDLY_LOCATION_NAME,
                self::CATEGORY_IDS,
                self::VISIBILITY
            ];

            /**
             * Product Collection
             *
             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $products
             */
            $products = $this->collectionFactory->create();
            $products->addAttributeToSelect($productAttributes);
            $stores = $this->_storeManager->getStores(true);
            if ($products->getSize()) {
                foreach ($products as $productInfo) {
                    $productDetails = $this->imagesAndCategoriesRepository
                        ->getBySku($productInfo->getSku());
                    if ($productDetails->getCategories()) {
                        $categoryIds = explode(
                            ",",
                            $productDetails->getCategories()
                        );
                        $this->assignCategories($productInfo->getId(), $categoryIds);
                    }
                    foreach ($stores as $store) {
                        $this->_storeManager->setCurrentStore($store->getId());
                        $updateAttributes = [
                            self::IMAGE_CUSTOM_URLS => $productDetails->getImages(),
                            self::VIDEO_THUMBNAIL_URL => $productDetails
                                ->getVideoThumbnail(),
                            self::VIDEO_URL => $productDetails->getVideos(),
                            self::CUSTOMER_FRIENDLY_LOCATION_NAME => $productDetails
                                ->getCustomerFriendlyLocationName(),
                            self::VISIBILITY => $productDetails->getProductVisibility(),
                        ];
                        $this->productAction->updateAttributes(
                            [$productInfo->getId()],
                            $updateAttributes,
                            $this->_storeManager->getStore()->getId()
                        );
                    }
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }
}
