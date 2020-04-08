<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/8/19
 * Time: 10:51 AM
 */

namespace Codilar\ComputePrice\Controller\Adminhtml\Price;

use Magento\Setup\Exception;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class ComputePrice
 *
 * @package Codilar\ComputePrice\Controller\Adminhtml\Price
 */
class ComputePrice extends Action
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
     * Result Page Factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        DirectoryList $directoryList,
        LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_logger = $logger;
        $this->directoryList = $directoryList;
    }

    /**
     * Execute Add Video Indexer
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $rootPath = $this->directoryList->getRoot();
            $command = "php " . $rootPath . "/bin/magento codilar:computeprice:forproducts";
            $access_log = $rootPath . "/var/log/computeproductsprice_access.log";
            $error_log = $rootPath . "/var/log/computeproductsprice_error.log";
            shell_exec($command . " > $access_log 2> $error_log &");
            /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
            $resultPage = $this->resultPageFactory->create();
            $resultPage->addBreadcrumb(__('Compute Price'), __('Compute Price'));
            $resultPage->getConfig()->getTitle()->prepend(__('Product Price will be computed in few time'));

            return $resultPage;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}