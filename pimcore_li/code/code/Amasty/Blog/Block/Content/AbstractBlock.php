<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Content;

use Amasty\Blog\Model\Networks;
use Magento\Cms\Model\Template\Filter;

class AbstractBlock extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Amasty\Blog\Helper\Data
     */
    protected $dataHelper;
    /**
     * @var \Amasty\Blog\Helper\Url
     */
    protected $urlHelper;
    /**
     * @var \Amasty\Blog\Helper\Settings
     */
    public $settingsHelper;
    /**
     * @var \Magento\Theme\Block\Html\Pager
     */
    protected $pager;
    /**
     * @var \Amasty\Blog\Model\ResourceModel\Posts\Collection
     */
    protected $postsCollection;

    /**
     * @var Filter
     */
    protected $filter;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \Amasty\Blog\Model\Posts
     */
    protected $postsModel;
    /**
     * @var Networks
     */
    protected $networksModel;
    /**
     * @var \Amasty\Blog\Model\Tags
     */
    protected $tagsModel;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManagerInterface;
    /**
     * @var \Amasty\Blog\Model\Categories
     */
    protected $categoriesModel;
    /**
     * @var \Amasty\Blog\Model\Lists
     */
    protected $listsModel;
    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    protected $context;

    /**
     * AbstractBlock constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Amasty\Blog\Helper\Data $dataHelper
     * @param \Magento\Framework\Registry $registry
     * @param \Amasty\Blog\Helper\Settings $settingsHelper
     * @param \Amasty\Blog\Helper\Url $urlHelper
     * @param Filter $filter
     * @param \Amasty\Blog\Model\Posts $postsModel
     * @param \Amasty\Blog\Model\Categories $categoriesModel
     * @param \Amasty\Blog\Model\Tags $tagsModel
     * @param Networks $networksModel
     * @param \Amasty\Blog\Model\ResourceModel\Posts\Collection $postsCollection
     * @param \Magento\Theme\Block\Html\Pager $pager
     * @param \Magento\Framework\ObjectManagerInterface $objectManagerInterface
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Helper\Data $dataHelper,
        \Magento\Framework\Registry $registry,
        \Amasty\Blog\Helper\Settings $settingsHelper,
        \Amasty\Blog\Helper\Url $urlHelper,
        \Magento\Cms\Model\Template\Filter $filter,
        \Amasty\Blog\Model\Posts $postsModel,
        \Amasty\Blog\Model\Categories $categoriesModel,
        \Amasty\Blog\Model\Tags $tagsModel,
        \Amasty\Blog\Model\Networks $networksModel,
        \Amasty\Blog\Model\Lists $listsModel,
        \Amasty\Blog\Model\ResourceModel\Posts\Collection $postsCollection,
        \Magento\Theme\Block\Html\Pager $pager,
        \Magento\Framework\ObjectManagerInterface $objectManagerInterface,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dataHelper = $dataHelper;
        $this->urlHelper = $urlHelper;
        $this->settingsHelper = $settingsHelper;
        $this->pager = $pager;
        $this->postsCollection = $postsCollection;
        $this->filter = $filter;
        $this->registry = $registry;
        $this->postsModel = $postsModel;
        $this->networksModel = $networksModel;
        $this->tagsModel = $tagsModel;
        $this->objectManagerInterface = $objectManagerInterface;
        $this->categoriesModel = $categoriesModel;
        $this->listsModel = $listsModel;
        $this->context = $context;
    }

    /**
     * Route to get configuration
     *
     * @var string
     */
    protected $_route = 'abstract';

    protected $_title = 'Default Blog Title';
    
    protected function _prepareBreadcrumbs()
    {
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs){
            $breadcrumbs->addCrumb('home', array(
                                'label' => __("Home"),
                                'title' => __("Home"),
                                'link' => $this->getBaseUrl('web')
                            ));
        }
        return $this;
    }

    protected function _preparePage()
    {
        //$head = $this->getLayout()->getBlock('head');

        $this->pageConfig->getTitle()->set($this->getMetaTitle());
        //if ($head){
            //$this->config->setTitle($this->getMetaTitle());
            //$this->pageConfig->getTitle()->set(__('Loyalty Program'));
            $this->pageConfig->setKeywords($this->getKeywords());
            $this->pageConfig->setDescription($this->escapeHtml(str_replace("\n", "", $this->getDescription())));
        //}

        $root = $this->getLayout()->getBlock('root');
        if ($root){
            
            $layout = $this->_getPageConfig()->getPageLayout($this->_helper()->getLayoutCode());
            if ($layout){
                $root->setTemplate($layout->getTemplate());
            }
        }

        $this->_prepareBreadcrumbs();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->_preparePage();

        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getMetaTitle()
    {
        return $this->getTitle();
    }

    public function getKeywords()
    {
        return '';
    }

    public function getDescription()
    {
        return '';
    }

    public function getHeaderHtml($post = null)
    {
        return $this->dataHelper->getHeaderHtml($post);
    }

    public function getFooterHtml($post = null)
    {
        return $this->dataHelper->getFooterHtml($post);
    }

}
