<?php

namespace MageArray\News\Controller\Adminhtml\Newspost;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class ExportXml
 * @package MageArray\News\Controller\Adminhtml\Newspost
 */
class ExportXml extends Action
{
    /**
     * @var FileFactory
     */
    protected $_fileFactory;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * ExportXml constructor.
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        PageFactory $resultPageFactory
    ) {
        $this->_fileFactory = $fileFactory;
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Exception
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();

        $fileName = 'News.xml';
        $exportBlock = $resultPage->getLayout()
            ->createBlock(
                'MageArray\News\Block\Adminhtml\Newspost\GridExport'
            );
        $content = $exportBlock->getExcelFile($fileName);
        return $this->_fileFactory
            ->create($fileName, $content, DirectoryList::VAR_DIR);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization
            ->isAllowed('MageArray_News::newscomment');
    }
}
