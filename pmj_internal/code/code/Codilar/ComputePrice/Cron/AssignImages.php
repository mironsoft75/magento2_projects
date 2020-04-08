<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/8/19
 * Time: 1:07 PM
 */

namespace Codilar\ComputePrice\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class AssignImages
 * @package Codilar\ComputePrice\Cron
 */
class AssignImages
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
     *
     * @param $directoryList
     * @param $logger
     */
    public function __construct
    (
        DirectoryList $directoryList,
        LoggerInterface $logger
    ) {
        $this->_logger = $logger;
        $this->directoryList = $directoryList;
    }

    /**
     * Execute
     */
    public function execute()
    {
        try{
            $rootPath = $this->directoryList->getRoot();
            $command = "php " . $rootPath . "/bin/magento indexer:reindex";
            $access_log = $rootPath . "/var/log/codilarMedia_access.log";
            $error_log = $rootPath . "/var/log/codilarMedia_error.log";
            shell_exec($command . " > $access_log 2> $error_log &");
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
        if(a){
            print("mjjkj");
        }

    }
}