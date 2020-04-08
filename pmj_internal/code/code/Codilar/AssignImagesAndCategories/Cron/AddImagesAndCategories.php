<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/10/19
 * Time: 4:29 PM
 */

namespace Codilar\AssignImagesAndCategories\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class AddImagesAndCategories
 * @package Codilar\AssignImagesAndCategories\Cron
 */
class AddImagesAndCategories
{
    /**
     * Logger
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
     * AssignImages constructor.
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
     * Execute
     */
    public function execute()
    {

        try {
            $rootPath = $this->directoryList->getRoot();
            $command = "php " . $rootPath . "/bin/magento codilar:addimagesandcategories";
            $access_log = $rootPath . "/var/log/codilarAddMedia_access.log";
            $error_log = $rootPath . "/var/log/codilarAddMedia_error.log";
            shell_exec($command . " > $access_log 2> $error_log &");
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }
}