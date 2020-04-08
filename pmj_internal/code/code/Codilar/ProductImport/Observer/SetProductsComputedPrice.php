<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24/5/19
 * Time: 4:39 PM
 */

namespace Codilar\ProductImport\Observer;

use Codilar\ProductImport\Helper\CategoryHelper;
use Codilar\ProductImport\Helper\Data;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Psr\Log\LoggerInterface;

/**
 * Class SetProductComputedPrice
 * @package Codilar\ProductImport\Observer
 */
class SetProductsComputedPrice implements ObserverInterface
{
    const NO = "No";
    const DELETE_PRODUCT = "delete_product";
    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var ProductRepositoryInterface
     */
    private $_productRepository;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var Data
     */
    protected $_priceHelper;
    /**
     * @var CategoryHelper
     */
    protected $_categoryHelper;

    /**
     * SetProductsComputedPrice constructor.
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param CategoryHelper $categoryHelper
     * @param DirectoryList $directoryList
     * @param Data $helper
     */
    public function __construct(
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        CategoryHelper $categoryHelper,
        DirectoryList $directoryList,
        Data $helper

    )
    {
        $this->_logger = $logger;
        $this->_productRepository = $productRepository;
        $this->_storeManager = $storeManager;
        $this->_categoryHelper = $categoryHelper;
        $this->_priceHelper = $helper;
        $this->directoryList = $directoryList;

    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        try {
            $stores = $this->_storeManager->getStores(true);
            // product save via normal method
//            if ($products = $observer->getEvent()->getBunch()) {
//                foreach ($products as $productInfo) {
//                    foreach ($stores as $store) {
//                        $product = $this->_productRepository
//                            ->get($productInfo['sku']);
//                        $product->setStockData(['qty' => $product->getPcs(), 'is_in_stock' => $product->getPcs() > 0]);
//                        $this->_storeManager->setCurrentStore($store->getId());
//                        $this->_productRepository->save($product);
//                        if ($product->getAttributeText(self::DELETE_PRODUCT) == self::NO) {
//                            $this->_priceHelper->updateIndividualProductStockData($product->getId());
//                        }
//                    }
//                }
//            }
            // product import via ssh
            if ($products = $observer->getEvent()->getBunch()) {
                $rootPath = $this->directoryList->getRoot();
                $command = "php " . $rootPath . "/bin/magento codilar:computeprice:forcentraltable";
                $access_log = $rootPath . "/var/log/productsave_access.log";
                $error_log = $rootPath . "/var/log/productsave_error.log";
                shell_exec($command . " > $access_log 2> $error_log &");
            }
            foreach ($products as $productInfo) {
                foreach ($stores as $store) {
                    $this->_storeManager->setCurrentStore($store->getId());
                    $product = $this->_productRepository
                        ->get($productInfo['sku']);
                    if ($product->getAttributeText(self::DELETE_PRODUCT) == self::NO) {
                        $this->_priceHelper->updateIndividualProductStockData($product->getId());
                    }
                }
            }
            foreach ($stores as $store) {
                $this->_priceHelper->updateProductAttributes($store);
                $this->_priceHelper->displayProducts($store);
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}
