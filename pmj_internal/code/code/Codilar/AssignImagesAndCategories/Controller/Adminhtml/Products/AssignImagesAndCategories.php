<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/10/19
 * Time: 12:45 PM
 */

namespace Codilar\AssignImagesAndCategories\Controller\Adminhtml\Products;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class AssignImagesAndCategories
 *
 * @package Codilar\AssignImagesAndCategories\Controller\Adminhtml\Products
 */
class AssignImagesAndCategories extends Action
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
     * AssignImagesAndCategories constructor.
     *
     * @param Action\Context $context Context
     * @param PageFactory $resultPageFactory PageFactory
     * @param DirectoryList $directoryList DirectoryList
     * @param LoggerInterface $logger LoggerInterface
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
            $command = "php " . $rootPath . "/bin/magento codilar:assignimagesandcategories";
            $access_log = $rootPath . "/var/log/assignimagesandcategories_access.log";
            $error_log = $rootPath . "/var/log/assignimagesandcategories_error.log";
            shell_exec($command . " > $access_log 2> $error_log &");
            /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
            $resultPage = $this->resultPageFactory->create();
            $resultPage->addBreadcrumb(__('Delete Products'), __('Delete Products'));
            $resultPage->getConfig()->getTitle()
                ->prepend(__('Products will be will be Assign to Images and Categories in few time and we will update via mail'));
            return $resultPage;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}