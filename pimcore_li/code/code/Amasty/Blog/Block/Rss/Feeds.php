<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Block\Rss;

use Amasty\Blog\Model\Posts;
use Magento\Framework\App\Rss\DataProviderInterface;

class Feeds extends \Magento\Framework\View\Element\AbstractBlock implements DataProviderInterface
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var Posts
     */
    private $postsModel;

    /**
     * @var \Magento\Framework\App\Rss\UrlBuilderInterface
     */
    private $rssUrlBuilder;
    /**
     * @var \Amasty\Blog\Model\Comments
     */
    private $commentsModel;

    /**
     * Feeds constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Rss\UrlBuilderInterface $rssUrlBuilder
     * @param Posts $postsModel
     * @param \Amasty\Blog\Model\Comments $commentsModel
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Rss\UrlBuilderInterface $rssUrlBuilder,
        Posts $postsModel,
        \Amasty\Blog\Model\Comments $commentsModel,
        array $data = []
    ) {
        $this->storeManager = $context->getStoreManager();
        parent::__construct($context, $data);
        $this->postsModel = $postsModel;
        $this->rssUrlBuilder = $rssUrlBuilder;
        $this->commentsModel = $commentsModel;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $action = $this->getRequest()->getActionName();
        $storeId = $this->getStoreId();
        $this->setCacheKey("amblog_rss_{$action}_{$storeId}");
        parent::_construct();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getRssData()
    {
        $feed = $this->getRequest()->getParam('record');
        $id = $this->getRequest()->getParam('id');
        $data = false;
        switch ($feed) {
            case 'post':
                $data = $this->getPostsFeed();
                break;
            case 'tags':
                $data = $this->getTagsFeed($id);
                break;
            case 'cats':
                $data = $this->getCatsFeed($id);
                break;
            case 'comments':
                $data = $this->getCommentsFeed($id);
                break;
        }
        return $data;
    }

    /**
     * @return int
     */
    protected function getStoreId()
    {
        $storeId = (int)$this->getRequest()->getParam('store_id');
        if ($storeId == null) {
            $storeId = $this->storeManager->getStore()->getId();
        }
        return $storeId;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheLifetime()
    {
        return 0;
    }

    /**
     * @return array
     */
    public function getFeeds()
    {
        $data = [];

        if ($this->isAllowed()) {
            $feeds[] = [
                'label' => __('Blog Posts'),
                'link' => $this->rssUrlBuilder->getUrl(['type' => 'amblog', 'record' => 'post']),
            ];
            $feeds[] = [
                'label' => __('Blog Tags'),
                'link' => $this->rssUrlBuilder->getUrl(['type' => 'amblog', 'record' => 'tags']),
            ];
            $feeds[] = [
                'label' => __('Blog Categories'),
                'link' => $this->rssUrlBuilder->getUrl(['type' => 'amblog', 'record' => 'cats']),
            ];
            $feeds[] = [
                'label' => __('Blog Comments'),
                'link' => $this->rssUrlBuilder->getUrl(['type' => 'amblog', 'record' => 'comments']),
            ];
            $data = [ 'group' => 'BlogPro', 'feeds' => $feeds  ];
        }

        return $data;
    }

    public function isAllowed()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthRequired()
    {
        return false;
    }
    
    
    public function getPostsFeed()
    {
        $collection = $this->postsModel->getCollection();

        if (!$this->storeManager->isSingleStoreMode()) {
            $collection->addStoreFilter($this->getStoreId());
        }

        $data = ['title' => __('Blog Post Feed'), 'description' => __('Blog Post Feed'), 'link' =>'asd', 'charset' => 'UTF-8'];

        $collection
            ->setDateOrder()
            ->setPageSize(10)
            ->addFieldToFilter('status', Posts::STATUS_ENABLED)
        ;

        foreach ($collection as $post) {
            $data['entries'][] = [
                'title'         => $post->getTitle(),
                'link'          => $post->getPostUrl(),
                'description'   => $post->getFullContent(),
                'lastUpdate'    => strtotime($post->getUpdatedAt()),
            ];
        }

        return $data;
    }

    public function getTagsFeed($id = false)
    {
        $collection = $this->postsModel->getCollection();

        if (!$this->storeManager->isSingleStoreMode()) {
            $collection->addStoreFilter($this->getStoreId());
        }

        if ($id) {
            $collection->addTagFilter($id);
        }

        $data = ['title' => __('Blog Tags Feed'), 'description' => __('Blog Tags Feed'), 'link' =>'asd', 'charset' => 'UTF-8'];

        $collection
            ->setDateOrder()
            ->setPageSize(10)
            ->addFieldToFilter('status', Posts::STATUS_ENABLED)
        ;

        foreach ($collection as $post) {
            $data['entries'][] = [
                'title'         => $post->getTitle(),
                'link'          => $post->getPostUrl(),
                'description'   => $post->getFullContent(),
                'lastUpdate'    => strtotime($post->getUpdatedAt()),
            ];
        }

        return $data;

    }

    public function getCatsFeed($id = false)
    {
        $collection = $this->postsModel->getCollection();

        if (!$this->storeManager->isSingleStoreMode()) {
            $collection->addStoreFilter($this->getStoreId());
        }

        if ($id) {
            $collection->addCategoryFilter($id);
        }

        $data = ['title' => __('Blog Tags Feed'), 'description' => __('Blog Tags Feed'), 'link' =>'asd', 'charset' => 'UTF-8'];

        $collection
            ->setDateOrder()
            ->setPageSize(10)
            ->addFieldToFilter('status', Posts::STATUS_ENABLED)
        ;

        foreach ($collection as $post) {
            $data['entries'][] = [
                'title'         => $post->getTitle(),
                'link'          => $post->getPostUrl(),
                'description'   => $post->getFullContent(),
                'lastUpdate'    => strtotime($post->getUpdatedAt()),
            ];
        }

        return $data;

    }

    public function getCommentsFeed($id)
    {
        $comments = [];

        $collection = $this->commentsModel->getCollection();

        if (!$this->storeManager->isSingleStoreMode()) {
            $collection->addStoreFilter($this->getStoreId());
        }

        if ($id) {
            $collection->addPostFilter($id);
        }

        $collection
            ->setDateOrder('DESC')
            ->setPageSize(10)
            ->addFieldToFilter('status', \Amasty\Blog\Model\Comments::STATUS_APPROVED)
        ;

        foreach ($collection as $comment) {
            $comments[] = [
                'title'         => $comment->getPost()->getTitle(),
                'link'          => $comment->getCommentUrl(),
                'description'   => $comment->getMessage(),
                'lastUpdate'    => strtotime($comment->getUpdatedAt()),
                'charset' => 'UTF-8',
            ];
        }
        return $comments;
    }

}
