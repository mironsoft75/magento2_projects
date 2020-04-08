<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 4/7/19
 * Time: 3:03 PM
 */

namespace Codilar\ProductImageAndVideos\Controller\Adminhtml\Video;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class ShowVideoLog extends Action
{
    /**
     * ShowImageLog constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
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
        $content = file_get_contents($rootPath . 'log/video_indexer.log');
        return $content;
    }
}
