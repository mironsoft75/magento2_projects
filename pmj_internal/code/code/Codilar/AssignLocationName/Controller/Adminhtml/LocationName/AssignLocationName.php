<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28/11/19
 * Time: 3:09 PM
 */

namespace Codilar\AssignLocationName\Controller\Adminhtml\LocationName;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class AssignImagesAndCategories
 *
 * @package Codilar\AssignImagesAndCategories\Controller\Adminhtml\Products
 */
class AssignLocationName extends Action
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
            $command = "php " . $rootPath . "/bin/magento codilar:assignlocationname";
            $access_log = $rootPath . "/var/log/assignlocationname_access.log";
            $error_log = $rootPath . "/var/log/assignlocationname_error.log";
            shell_exec($command . " > $access_log 2> $error_log &");
            /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
            $resultPage = $this->resultPageFactory->create();
            $resultPage->addBreadcrumb(__('Delete Products'), __('Delete Products'));
            $resultPage->getConfig()->getTitle()
                ->prepend(__('Products will be will be Assign to LocationName in few time and we will update via mail'));
            return $resultPage;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}