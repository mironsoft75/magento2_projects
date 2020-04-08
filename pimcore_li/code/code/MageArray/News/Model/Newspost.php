<?php

namespace MageArray\News\Model;

use MageArray\News\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

/**
 * Class Newspost
 * @package MageArray\News\Model
 */
class Newspost extends AbstractModel
{
    /**
     * @var Data
     */
    protected $_dataHelper;
    /**
     * @var CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('MageArray\News\Model\ResourceModel\Newspost');
    }

    /**
     * @var UrlInterface
     */
    protected $_urlModel;

    /**
     * Newspost constructor.
     * @param Context $context
     * @param Registry $registry
     * @param UrlInterface $urlModel
     * @param CollectionFactory $productCollectionFactory
     * @param Data $dataHelper
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        UrlInterface $urlModel,
        CollectionFactory $productCollectionFactory,
        Data $dataHelper,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->_dataHelper = $dataHelper;
        $this->_urlModel = $urlModel;
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param $urlKey
     * @param $storeId
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkUrlKey($urlKey, $storeId)
    {
        return $this->_getResource()
            ->checkUrlKey($urlKey, $storeId);
    }

    /**
     * @return string
     */
    public function getPostUrl()
    {
        $newsTitle = $this->_dataHelper
            ->getStoreConfig('magearray_news/general/url_prefix');
        $urlSuffixConfig = $this->_dataHelper
            ->getStoreConfig('magearray_news/general/url_suffix');
        $urlSuffix = "";
        if (!empty($urlSuffixConfig) && $urlSuffixConfig != "") {
            $urlSuffix = '.' . $urlSuffixConfig;
        }
        return $this->_urlModel
                ->getUrl() . $newsTitle . '/' . $this
                ->getUrlKey() . $urlSuffix;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        $dateFormat = $this->_dataHelper
            ->getStoreConfig('magearray_news/general/date_format');
        return date($dateFormat, strtotime($this->getPublishDate()));
    }

    /**
     * @return mixed
     */
    public function getDisplayViews()
    {
        return $this->_dataHelper
            ->getStoreConfig('magearray_news/display_settings/display_views');
    }

    /**
     * @return mixed
     */
    public function getDisplayShare()
    {
        return $this->_dataHelper
            ->getStoreConfig('magearray_news/display_settings/display_share');
    }

    /**
     * @return mixed
     */
    public function getShareAbove()
    {
        return $this->_dataHelper
            ->getStoreConfig('magearray_news/display_settings/share_above');
    }

    /**
     * @return mixed
     */
    public function getShareBelow()
    {
        return $this->_dataHelper
            ->getStoreConfig('magearray_news/display_settings/share_below');
    }

    /**
     * @return mixed
     */
    public function getTagonpost()
    {
        return $this->_dataHelper
            ->getStoreConfig('magearray_news/display_settings/display_tags');
    }

    /**
     * @return $this
     */
    public function getRelatedPosts()
    {
        $collection = $this->getCollection()->setPostFilter()
            ->addFieldToFilter(
                'main_table.newspost_id',
                ['neq' => $this->getId()]
            );
        $collection->getSelect()->joinLeft(
            [
                're' => $this->getResource()
                    ->getTable('magearray_news_post_relatedpost')
            ],
            'main_table.newspost_id = re.related_id',
            ['position']
        )->where(
            're.newspost_id = ?',
            $this->getId()
        );
        return $collection;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getRelatedProducts()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->getSelect()->joinLeft(
            [
                're' => $this->getResource()
                    ->getTable('magearray_news_post_relatedproduct')
            ],
            'e.entity_id = re.related_id',
            ['position']
        )
            ->where(
                're.newspost_id = ?',
                $this->getId()
            );

        return $collection;
    }

    /**
     * @return $this
     */
    public function beforeSave()
    {
        $this->_cacheManager->clean();
        return $this;
    }

    public function saveAsRevision()
    {
        $clone = clone $this;
        $clone->setParentId($this->getId())
            ->setType('revision')
            ->setIsActive(2);
        $clone->unsetData('newspost_id');
        $clone->save();

        return $clone;
    }
}
