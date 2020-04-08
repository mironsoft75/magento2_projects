<?php

namespace MageArray\News\Block;

use MageArray\News\Helper\Data;
use MageArray\News\Model\Customerdata;
use MageArray\News\Model\NewscommentFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Comments
 * @package MageArray\News\Block
 */
class Comments extends Template
{
    /**
     * @var null
     */
    protected $_commentCollection = null;
    /**
     * @var NewscommentFactory
     */
    protected $_newscommentFactory;
    /**
     * @var Customerdata
     */
    protected $_customerData;
    /**
     * @var Registry
     */
    protected $_coreRegistry;
    /**
     * @var ResolverInterface
     */
    protected $_localeResolver;

    /**
     * Comments constructor.
     * @param Context $context
     * @param NewscommentFactory $newscommentFactory
     * @param Data $dataHelper
     * @param Customerdata $customerData
     * @param ResolverInterface $localeResolver
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        NewscommentFactory $newscommentFactory,
        Data $dataHelper,
        Customerdata $customerData,
        ResolverInterface $localeResolver,
        Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_newscommentFactory = $newscommentFactory;
        $this->_dataHelper = $dataHelper;
        $this->_customerData = $customerData;
        $this->_coreRegistry = $coreRegistry;
        $this->_localeResolver = $localeResolver;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getComments($id)
    {
        $newscommentModel = $this->_newscommentFactory->create();
        $newsCollection = $newscommentModel->getCollection()
            ->addFieldToFilter('newspost_id', $id)
            ->addFieldToFilter('comment_status', 1);
        return $newsCollection;
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function getCollection($id)
    {
        if (empty($this->_commentCollection)) {
            $this->_commentCollection = $this->getComments($id);
            $this->_commentCollection
                ->setCurPage($this->getCurrentPage());
            $this->_commentCollection
                ->setPageSize($this->_dataHelper->getCommentsPerPage());
        }
        return $this->_commentCollection;
    }

    /**
     * @return bool
     */
    public function getLoggedInDetail()
    {
        return $this->_customerData->isLoggedIn();
    }

    /**
     * @return int|mixed
     */
    public function getCurrentPage()
    {
        return $this->getData('current_page') ?
            $this->getData('current_page') : 1;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        if (!$this->hasData('post')) {
            $this->setData(
                'post',
                $this->_coreRegistry->registry('current_post')
            );
        }
        return $this->getData('post');
    }

    /**
     * @return string
     */
    public function getLocaleCode()
    {
        return $this->_localeResolver->getLocale();
    }

    /**
     * @return null|string
     */
    public function getPager()
    {
        $id = $this->getRequest()->getParam('id');
        $pager = $this->getChildBlock('comment_list_pager');
        if ($pager instanceof DataObject) {
            $commentsPerPage = $this->_dataHelper->getCommentsPerPage();
            $pager->setAvailableLimit([$commentsPerPage => $commentsPerPage]);
            $pager->setTotalNum($this->getCollection($id)->getSize());
            $pager->setCollection($this->getCollection($id));
            $pager->setShowPerPage(true);
            return $pager->toHtml();
        }
        return null;
    }
}
