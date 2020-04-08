<?php

namespace MageArray\News\Controller\Adminhtml;

use MageArray\News\Model\NewscommentFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Helper\Js;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Newscomment
 * @package MageArray\News\Controller\Adminhtml
 */
abstract class Newscomment extends \Magento\Backend\App\Action
{
    /**
     * @var Js
     */
    protected $_jsHelper;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var ForwardFactory
     */
    protected $_resultForwardFactory;
    /**
     * @var LayoutFactory
     */
    protected $_resultLayoutFactory;
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var RawFactory
     */
    protected $_resultRawFactory;

    /**
     * @var NewscommentFactory
     */
    protected $_newscommentFactory;
    /**
     * @var Registry
     */
    protected $_coreRegistry;
    /**
     * @var FileFactory
     */
    protected $_fileFactory;

    /**
     * Newscomment constructor.
     * @param Context $context
     * @param NewscommentFactory $newscommentFactory
     * @param Registry $coreRegistry
     * @param FileFactory $fileFactory
     * @param PageFactory $resultPageFactory
     * @param LayoutFactory $resultLayoutFactory
     * @param ForwardFactory $resultForwardFactory
     * @param RawFactory $resultRawFactory
     * @param Js $jsHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        NewscommentFactory $newscommentFactory,
        Registry $coreRegistry,
        FileFactory $fileFactory,
        PageFactory $resultPageFactory,
        LayoutFactory $resultLayoutFactory,
        ForwardFactory $resultForwardFactory,
        RawFactory $resultRawFactory,
        Js $jsHelper,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_newscommentFactory = $newscommentFactory;
        $this->_fileFactory = $fileFactory;
        $this->_storeManager = $storeManager;
        $this->_jsHelper = $jsHelper;
        $this->_resultRawFactory = $resultRawFactory;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultLayoutFactory = $resultLayoutFactory;
        $this->_resultForwardFactory = $resultForwardFactory;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MageArray_News::newscomment');
    }
}
