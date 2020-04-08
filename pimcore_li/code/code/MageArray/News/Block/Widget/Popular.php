<?php

namespace MageArray\News\Block\Widget;

use MageArray\News\Model\NewspostFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

class Popular extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'widget/popular.phtml';

    protected $_modelNewsFactory;

    public function __construct(
        Context $context,
        array $data = [],
        NewspostFactory $modelNewsFactory
    ) {
        $this->_modelNewsFactory = $modelNewsFactory;
        parent::__construct($context, $data);
        $count = $this->getCount();
        $now = date('Y-m-d');
        $collection = $this->_modelNewsFactory->create()->getCollection();
        $collection->setActiveFilter(true)->setPostFilter();
        $collection->setStoreFilter($this->getStoreId());
        $collection->addFieldToFilter('publish_date', ['lteq' => $now]);
        $collection->setOrder('views', 'DESC');
        if (isset($count) && $count != '') {
            $collection->setPageSize($count);
        } else {
            $collection->setPageSize(5);
        }
        $this->setCollection($collection);
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
