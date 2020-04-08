<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/5/19
 * Time: 10:04 AM
 */

namespace Codilar\ProductImport\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class SetMasterTableComputedPrice
 * @package Codilar\ProductImport\Observer
 */
class SetMasterTableComputedPrice implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * SetMasterTableComputedPrice constructor.
     * @param DirectoryList $directoryList
     * @param LoggerInterface $logger
     */
    public function __construct(
        DirectoryList $directoryList,
        LoggerInterface $logger
    )
    {
        $this->_logger = $logger;
        $this->directoryList = $directoryList;

    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return bool|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        try {
            $rootPath = $this->directoryList->getRoot();
            $command = "php " . $rootPath . "/bin/magento codilar:computeprice:forcentraltable";
            $access_log = $rootPath . "/var/log/computeprice_access.log";
            $error_log = $rootPath . "/var/log/computeprice_error.log";
            shell_exec($command . " > $access_log 2> $error_log &");
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}