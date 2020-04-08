<?php

namespace MageArray\News\Block\Postview;

use MageArray\News\Helper\Data;
use MageArray\News\Model\Newspost;
use MageArray\News\Model\NewspostFactory;
use Magento\Cms\Model\Page;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class RelatedPosts
 * @package MageArray\News\Block\Postview
 */
class RelatedPosts extends Template
{
    /**
     * @var
     */
    protected $_postCollection;

    /**
     * @var Registry
     */
    protected $_coreRegistry;
    /**
     * @var Newspost
     */
    protected $_post;
    /**
     * @var NewspostFactory
     */
    protected $_newspostFactory;
    /**
     * @var Data
     */
    protected $_dataHelper;
    /**
     * @var FilterProvider
     */
    protected $_filterProvider;

    /**
     * RelatedPosts constructor.
     * @param Context $context
     * @param Newspost $post
     * @param NewspostFactory $postFactory
     * @param Registry $coreRegistry
     * @param Data $dataHelper
     * @param FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        Newspost $post,
        NewspostFactory $postFactory,
        Registry $coreRegistry,
        Data $dataHelper,
        FilterProvider $filterProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_post = $post;
        $this->_newspostFactory = $postFactory;
        $this->_dataHelper = $dataHelper;
        $this->_coreRegistry = $coreRegistry;
        $this->_filterProvider = $filterProvider;
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $this->_postCollection = $this->getPost()->getRelatedPosts()
            ->setPageSize($this->_dataHelper->getRelatedPostNumber());

        $this->_postCollection->getSelect()->order('re.position', 'ASC');

        return $this;
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function getShortDescription($id)
    {
        $newsModel = $this->_newspostFactory->create();
        $shortContent = $newsModel->load($id)->getShortContent();
        return $this->_filterProvider->getBlockFilter()->filter($shortContent);
    }

    /**
     * @return bool
     */
    public function displayPosts()
    {
        return $this->_dataHelper->getRelatedPostDisplay();
    }

    /**
     * @return mixed
     */
    public function getRelatedpost()
    {
        if (empty($this->_postCollection)) {
            $this->_prepareCollection();
        }
        return $this->_postCollection;
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
     * @return array
     */
    public function getIdentities()
    {
        return [
            Page::CACHE_TAG . '_relatedposts_' . $this->getPost()->getId()
        ];
    }

    /**
     * @param $item
     * @param $width
     * @return bool|string
     */
    public function getImageUrl($item, $width)
    {
        return $this->_dataHelper->resize($item, $width);
    }

    /**
     * @param $item
     * @param $width
     * @return bool|string
     */
    public function getThumbImageUrl($item, $width)
    {
        return $this->_dataHelper->resizeThumb($item, $width);
    }

    /**
     * @return string
     */
    public function getPlaceHolderImage()
    {
        return $this->getViewFileUrl(
            'MageArray_News::images/news-placeholder.jpg'
        );
    }
}
