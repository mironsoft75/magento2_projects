<?php

namespace MageArray\News\Block\Widget;

use MageArray\News\Helper\Data;
use MageArray\News\Model\NewspostFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

/**
 * Class Archive
 * @package MageArray\News\Block\Widget
 */
class Archive extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'widget/archive.phtml';
    /**
     * @var NewspostFactory
     */
    protected $_modelNewsFactory;
    /**
     * @var Data
     */
    protected $_dataHelper;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    protected $_newsCollection;
    /**
     * @var
     */
    protected $_monthyear;

    /**
     * Archive constructor.
     * @param Context $context
     * @param Data $dataHelper
     * @param array $data
     * @param NewspostFactory $modelNewsFactory
     */
    public function __construct(
        Context $context,
        Data $dataHelper,
        array $data = [],
        NewspostFactory $modelNewsFactory
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_modelNewsFactory = $modelNewsFactory;
        parent::__construct($context, $data);
        $count = $this->getCount();
        $now = date('Y-m-d');
        $this->_newsCollection = $this->_modelNewsFactory->create()
            ->getCollection();
        $this->_newsCollection->setActiveFilter(true)->setPostFilter();
        $this->_newsCollection->setStoreFilter($this->getStoreId());
        $this->_newsCollection->addFieldToFilter(
            'publish_date',
            ['lteq' => $now]
        );
        $this->_newsCollection->setOrder('publish_date', 'DESC');
        // if (isset($count) && $count != '') {
        // $this->_newsCollection->setPageSize($count);
        // } else {
        // $this->_newsCollection->setPageSize(5);
        // }
    }

    /**
     * @return array
     */
    public function monthList()
    {
        if (!is_array($this->_monthyear)) {
            $this->_monthyear = [];

            foreach ($this->_newsCollection as $newspost) {
                $time = strtotime($newspost->getData('publish_date'));
                $this->_monthyear[date('Y-m', $time)] = $time;
            }
        }
        return $this->_monthyear;
    }

    /**
     * @param $time
     * @return mixed
     */
    public function getYear($time)
    {
        return date('Y', $time);
    }

    /**
     * @param $time
     * @return mixed
     */
    public function getMonth($time)
    {
        return __(date('F', $time));
    }

    /**
     * @param $time
     * @return string
     */
    public function getArchiveUrl($time)
    {
        return $this->getUrl() . 'news_archive/' . date('Y-m', $time);
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
}
