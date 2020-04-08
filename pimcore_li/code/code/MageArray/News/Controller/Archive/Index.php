<?php

namespace MageArray\News\Controller\Archive;

use MageArray\News\Helper\Data;
use MageArray\News\Helper\Index\View;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package MageArray\News\Controller\Archive
 */
class Index extends Action
{
    /**
     * @var View
     */
    protected $_viewHelper;
    /**
     * @var Data
     */
    protected $_dataHelper;
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param View $viewHelper
     * @param PageFactory $resultPageFactory
     * @param Data $dataHelper
     */
    public function __construct(
        Context $context,
        View $viewHelper,
        PageFactory $resultPageFactory,
        Data $dataHelper,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_viewHelper = $viewHelper;
        $this->_dataHelper = $dataHelper;
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $page = $this->_resultPageFactory->create(false, ['isIsolated' => true]);
        $date = $this->getRequest()->getParam('date');
        $pageNo = $this->getRequest()->getParam('p');
        $date = explode('-', $date);
        $date[2] = '01';
        $time = strtotime(implode('-', $date));

        if (!$time || count($date) != 3) {
            $this->_forward('index', 'noroute', 'cms');
            return;
        }

        $registry = $this->_coreRegistry;
        $registry->register('current_news_archive_year', (int)$date[0]);
        $registry->register('current_news_archive_month', (int)$date[1]);
        $time = strtotime($date[0] . '-' . $date[1] . '-01');
        $page->getConfig()->getTitle()
            ->set(__('Monthly Archives : ' .
                date('F', $time) . ' ' . date('Y', $time)));

        $this->_viewHelper->prepareAndRender($page, $pageNo);

        $breadcrumbShow = $this->_dataHelper
            ->getStoreConfig('magearray_news/general/breadcrumb');
        if ($breadcrumbShow == 1) {
            $listUrl = $this->_dataHelper
                ->getStoreConfig('magearray_news/general/list_url');
            $breadcrumbs = $page->getLayout()
                ->getBlock('breadcrumbs');
            $breadcrumbs->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Home'),
                    'link' => $this->_url->getUrl('')
                ]
            );
            $breadcrumbs->addCrumb(
                'magearray_news',
                [
                    'label' => __('News Posts'),
                    'title' => __('News Posts'),
                    'link' => $this->_url->getUrl() . $listUrl
                ]
            );
            $breadcrumbs->addCrumb(
                'archive_view',
                [
                    'label' => __(date('F', $time) . ' ' . date('Y', $time)),
                    'title' => __(date('F', $time) . ' ' . date('Y', $time))
                ]
            );
        }
        return $page;
    }
}
