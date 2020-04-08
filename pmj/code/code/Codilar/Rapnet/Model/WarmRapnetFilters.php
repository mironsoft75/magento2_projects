<?php

namespace Codilar\Rapnet\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class WarmRapnetFilters
 * @package Codilar\CreditPayment\Model
 */
class WarmRapnetFilters extends AbstractModel
{
    /**
     * @var Context
     */
    private $context;
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * WarmRapnetFilters constructor.
     * @param Context $context
     * @param DirectoryList $directoryList
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        DirectoryList $directoryList,
        Registry $registry
    ) {
        parent::__construct($context, $registry);
        $this->context = $context;
        $this->directoryList = $directoryList;
    }

    /**
     * @return bool
     */
    public function warmRapnetFilters()
    {
        $rootPath = $this->directoryList->getRoot();
        $command = "php " . $rootPath . "/bin/magento codilar:rapnet:warmfilters";
        $access_log = $rootPath . "/var/log/rapnetwarm_access.log";
        $error_log = $rootPath . "/var/log/rapnetwarm_error.log";
        shell_exec($command . " > $access_log 2> $error_log &");
        return true;
    }
}
