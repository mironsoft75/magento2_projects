<?php
/**
 * @package   CategoryImporter
 * @author    Splash
 */

namespace Codilar\DeleteProducts\Controller\Adminhtml\Products;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class DeleteProducts
 *
 * @package Codilar\DeleteProducts\Controller\Adminhtml\Products
 */
class DeleteProducts extends Action
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
     * Execute DeleteProducts
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $rootPath = $this->directoryList->getRoot();
            $command = "php " . $rootPath . "/bin/magento codilar:deleteproducts";
            $access_log = $rootPath . "/var/log/deleteproducts_access.log";
            $error_log = $rootPath . "/var/log/deleteproducts_error.log";
            shell_exec($command . " > $access_log 2> $error_log &");
            /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
            $resultPage = $this->resultPageFactory->create();
            $resultPage->addBreadcrumb(__('Delete Products'), __('Delete Products'));
            $resultPage->getConfig()->getTitle()
                ->prepend(__('Products will be deleted in few time and we will update via mail'));
            return $resultPage;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}