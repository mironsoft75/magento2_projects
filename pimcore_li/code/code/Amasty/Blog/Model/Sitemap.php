<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model;

use Amasty\Blog\Model\ResourceModel\Posts\Collection as postsCollection;

class Sitemap extends \Magento\Framework\Model\AbstractModel
{
    const AMBLOG_TYPE_BLOG = 'blog';
    const AMBLOG_TYPE_POST = 'post';
    const AMBLOG_TYPE_CATEGORY = 'category';
    const AMBLOG_TYPE_TAG = 'tag';
    const AMBLOG_TYPE_ARCHIVE = 'archive';

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;
    /**
     * @var \Amasty\Blog\Helper\Url
     */
    private $urlHelper;
    /**
     * @var ResourceModel\Posts\Collection
     */
    private $postsCollection;

    protected $storeManagerInterface;

    /**
     * @var ResourceModel\Categories\Collection
     */
    private $categoriesCollection;

    /**
     * @var ResourceModel\Tags\Collection
     */
    private $tagsCollection;

    /**
     * @var \Amasty\Blog\Helper\Settings
     */
    private $settingsHelper;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Amasty\Blog\Helper\Url $urlHelper,
        \Amasty\Blog\Helper\Settings $settingsHelper,
        postsCollection $postsCollection,
        \Amasty\Blog\Model\ResourceModel\Categories\Collection $categoriesCollection,
        \Amasty\Blog\Model\ResourceModel\Tags\Collection $tagsCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection  = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dateTime = $dateTime;
        $this->urlHelper = $urlHelper;
        $this->postsCollection = $postsCollection;

        $this->storeManagerInterface = $storeManagerInterface;
        $this->categoriesCollection = $categoriesCollection;
        $this->tagsCollection = $tagsCollection;
        $this->settingsHelper = $settingsHelper;
    }

    public function generateLinks($storeId = null)
    {
        if ($storeId) {
            $this->setStoreId($storeId);
        }

        $links = [];
        $currentDate = $this->dateTime->gmtDate('Y-m-d');
        $storeId = $this->getStoreId() ? $this->getStoreId() : $this->storeManagerInterface->getStore()->getId();

        $links[] = [
            'url'  => $this->urlHelper->getUrl(),
            'date' => $currentDate,
        ];

        # Import Posts
        $posts = $this->postsCollection;
        if (!$this->storeManagerInterface->isSingleStoreMode()) {
            $posts->addStoreFilter($storeId);
        }

        $posts
            ->setDateOrder()
            ->addFieldToFilter('status', \Amasty\Blog\Model\Posts::STATUS_ENABLED);

        foreach ($posts as $post) {
            $post->setStoreId($storeId);
            $links[] = [
                'url'  => $post->getPostUrl(),
                'date' => $currentDate,
            ];
        }

        # Import Categories
        $categories = $this->categoriesCollection;
        if (!$this->storeManagerInterface->isSingleStoreMode()) {
            $categories->addStoreFilter($storeId);
        }

        $categories
            ->setSortOrder('asc')
            ->addFieldToFilter('status', \Amasty\Blog\Model\Categories::STATUS_ENABLED);

        foreach ($categories as $category) {
            $category->setStoreId($storeId);
            $links[] = [
                'url'  => $category->getUrl(),
                'date' => $currentDate,
            ];
        }

        # Import Tags
        $tags = $this->tagsCollection;
        $tags
            ->addWieghtData($storeId)
            ->setMinimalPostCountFilter($this->settingsHelper->getTagsMinimalPostCount())
            ->setPostStatusFilter(\Amasty\Blog\Model\Posts::STATUS_ENABLED)
            ->setNameOrder()
        ;

        foreach ($tags as $tag) {
            $tag->setStoreId($storeId);
            $links[] = [
                'url'  => $tag->getTagUrl(),
                'date' => $currentDate,
            ];
        }
        
        return $links;
    }
}
