<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 4/7/19
 * Time: 12:29 PM
 */

namespace Codilar\ProductImageAndVideos\Controller\Adminhtml\Images;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class ShowImageLog
 * @package Codilar\ProductImageAndVideos\Controller\Adminhtml\Images
 */
class ShowImageLog extends Action
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var PageFactory
     */
    private $resultPageFactory;
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * ShowImageLog constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Filesystem $filesystem
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Filesystem $filesystem,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->filesystem = $filesystem;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * @return false|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result= $this->getImageLogData();
        return $resultJson->setData(['success' => $result]);
    }

    /**
     * @return false|string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getImageLogData()
    {
        $rootPath = $this->filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)->getAbsolutePath();
        $content = file_get_contents($rootPath . 'log/images_indexer.log');
        return $content;
    }
}
