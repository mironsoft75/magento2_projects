<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/9/19
 * Time: 10:04 AM
 */

namespace Codilar\ComputePrice\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;


/**
 * Class UpdateIndexers
 * @package Codilar\ComputePrice\Observer
 */
class UpdateIndexers implements ObserverInterface
{
    /**
     * Logger Interface
     *
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * Directory List
     *
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * UpdateIndexers constructor.
     *
     * @param DirectoryList $directoryList
     * @param LoggerInterface $logger
     */
    public function __construct
    (
        DirectoryList $directoryList,
        LoggerInterface $logger
    )
    {
        $this->_logger = $logger;
        $this->directoryList = $directoryList;
    }

    /**
     * Execute Function
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $rootPath = $this->directoryList->getRoot();
            $access_log = $rootPath . "/var/log/updateIndexers_access.log";
            $error_log = $rootPath . "/var/log/updateIndexers_error.log";
            $command = "php " . $rootPath . "/bin/magento indexer:reindex design_config_grid" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reindex customer_grid" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reindex catalog_category_product" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reindex catalog_product_category" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reindex catalogrule_rule" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reindex catalog_product_attribute" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reindex inventory" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reindex catalogrule_product" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reindex cataloginventory_stock" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reindex catalog_product_price" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reindex catalogsearch_fulltext" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reset codilar_product_videos" . " && " .
                "php " . $rootPath . "/bin/magento indexer:reset codilar_product_images";
            shell_exec($command . " > $access_log 2> $error_log &");
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }
}