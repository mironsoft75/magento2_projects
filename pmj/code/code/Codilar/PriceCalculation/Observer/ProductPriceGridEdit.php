<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/

namespace Codilar\PriceCalculation\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class ProductPriceGridEdit
 * @package Codilar\PriceCalculation\Observer
 */
class ProductPriceGridEdit implements ObserverInterface
{
    const DIAMOND_PRODUCT = "Diamond Product";
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * ProductPriceGridEdit constructor.
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
            return true;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}
