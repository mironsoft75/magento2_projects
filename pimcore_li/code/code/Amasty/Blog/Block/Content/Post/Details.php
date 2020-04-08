<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Content\Post;

use Amasty\Blog\Model\ResourceModel\Comments\CollectionFactory;

class Details extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Amasty\Blog\Helper\Data
     */
    protected $helperData;
    /**
     * @var \Amasty\Blog\Helper\Date
     */
    protected $helperDate;
    /**
     * @var \Amasty\Blog\Helper\Settings
     */
    public $helperSettings;
    /**
     * @var CollectionFactory
     */
    protected $commentsCollectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManagerInterface;
    /**
     * @var \Amasty\Blog\Model\Tags
     */
    private $tagsModel;
    /**
     * @var \Amasty\Blog\Model\Categories
     */
    private $categoriesModel;
    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    private $context;

    /**
     * Details constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Amasty\Blog\Helper\Data $helperData
     * @param \Amasty\Blog\Helper\Date $helperDate
     * @param \Amasty\Blog\Model\Tags $tagsModel
     * @param \Amasty\Blog\Model\Categories $categoriesModel
     * @param Collection $commentsCollection
     * @param \Amasty\Blog\Helper\Settings $helperSettings
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Helper\Data $helperData,
        \Amasty\Blog\Helper\Date $helperDate,
        \Amasty\Blog\Model\Tags $tagsModel,
        \Amasty\Blog\Model\Categories $categoriesModel,
        \Amasty\Blog\Model\ResourceModel\Comments\CollectionFactory $commentsCollectionFactory,
        \Amasty\Blog\Helper\Settings $helperSettings,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helperData = $helperData;
        $this->helperDate = $helperDate;
        $this->helperSettings = $helperSettings;
        $this->commentsCollection = $commentsCollectionFactory->create();
        $this->tagsModel = $tagsModel;
        $this->categoriesModel = $categoriesModel;
        $this->context = $context;
    }

    protected $_post;

    public function setPost($post)
    {
        $this->_post = $post;
        return $this;
    }
    
    public function getPost()
    {
        return $this->_post;
    }

    public function isPost()
    {
        return ($this->getRequest()->getActionName() == 'post');
    }

    public function renderDate($datetime)
    {
        return $this->helperDate->renderDate($datetime);
    }

    public function getLeaveCommentUrl()
    {
        return $this->getPost()->getPostUrl()."#form";
    }

    public function getCommentsUrl()
    {
        return $this->getPost()->getPostUrl()."#comments";
    }

    public function getCommentsCount()
    {
        if (!$this->context->getStoreManager()->isSingleStoreMode()){
            $this->commentsCollection->addStoreFilter($this->context->getStoreManager()->getStore()->getId());
        }

        $this->commentsCollection
            ->addPostFilter($this->getPost()->getId())
            ->addActiveFilter()
            ;
        return $this->commentsCollection->getSize();
    }

    public function getTagsHtml()
    {
        $tagDetails = $this->getLayout()->createBlock('Amasty\Blog\Block\Content\Post\Details');
        if ($tagDetails){
            $tagDetails
                ->setPost($this->getPost())
                ->setTemplate('Amasty_Blog::list/tags.phtml');
            ;
            return $tagDetails->toHtml();
        }
        return false;
    }

    public function getCategoriesHtml()
    {
        $catDetails = $this->getLayout()->createBlock('Amasty\Blog\Block\Content\Post\Details');
        if ($catDetails){
            $catDetails
                ->setPost($this->getPost())
                ->setTemplate('Amasty_Blog::list/categories.phtml');
                ;
            return $catDetails->toHtml();
        }
        return false;
    }


    public function getTags()
    {
        $tags = $this->tagsModel->getCollection();
        $tags->addPostFilter($this->getPost()->getId());

        return $tags;
    }


    public function getCategories()
    {
        $categories = $this->categoriesModel->getCollection();
        $categories
            ->addPostFilter($this->getPost()->getId())
            ->addFieldToFilter('status', \Amasty\Blog\Model\Categories::STATUS_ENABLED)
        ;

        if (!$this->context->getStoreManager()->isSingleStoreMode()){
            $categories->addStoreFilter($this->context->getStoreManager()->getStore()->getId());
        }
        return $categories;
    }

    public function showAuthor()
    {
        return $this->helperSettings->getShowAuthor();
    }

    public function useGoogleProfile()
    {
        return !!$this->getPost()->getPostedBy() && !!$this->getPost()->getGoogleProfile();
    }

    public function getGoogleProfileUrl()
    {
        return $this->getPost()->getGoogleProfile();
    }

    public function getColorClass()
    {
        return $this->helperSettings->getIconColorClass();
    }
}