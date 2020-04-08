<?php

namespace MageArray\News\Controller\Adminhtml;

use MageArray\News\Model\NewscatFactory;
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
 * Class Newscat
 * @package MageArray\News\Controller\Adminhtml
 */
abstract class Newscat extends \Magento\Backend\App\Action
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
     * @var NewscatFactory
     */
    protected $_newscatFactory;
    /**
     * @var Registry
     */
    protected $_coreRegistry;
    /**
     * @var FileFactory
     */
    protected $_fileFactory;

    /**
     * Newscat constructor.
     * @param Context $context
     * @param NewscatFactory $newscatFactory
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
        NewscatFactory $newscatFactory,
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
        $this->_newscatFactory = $newscatFactory;
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
        return $this->_authorization->isAllowed('MageArray_News::newscat');
    }
}
