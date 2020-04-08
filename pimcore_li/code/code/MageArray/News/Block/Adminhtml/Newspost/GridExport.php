<?php

namespace MageArray\News\Block\Adminhtml\Newspost;

use MageArray\News\Model\NewspostFactory;
use MageArray\News\Model\Status;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Framework\Registry;

/**
 * Class GridExport
 * @package MageArray\News\Block\Adminhtml\Newspost
 */
class GridExport extends Extended
{
    /**
     * @var NewspostFactory
     */
    protected $_newspostFactory;
    /**
     * @var Registry
     */
    protected $_coreRegistry;
    /**
     * @var Status
     */
    protected $_status;

    /**
     * GridExport constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Data $backendHelper
     * @param NewspostFactory $newspostFactory
     * @param Status $status
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Data $backendHelper,
        NewspostFactory $newspostFactory,
        Status $status,
        array $data = []
    ) {
        $this->_newspostFactory = $newspostFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_status = $status;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('newsGrid');
        $this->setDefaultSort('newspost_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_newspostFactory->create()->getCollection()->setPostFilter();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
            ]
        );

        $this->addColumn(
            'tags',
            [
                'header' => __('Tags'),
                'index' => 'tags',
            ]
        );

        $this->addColumn(
            'short_content',
            [
                'header' => __('Short Content'),
                'index' => 'short_content',
            ]
        );

        $this->addColumn(
            'content',
            [
                'header' => __('Content'),
                'index' => 'content',
            ]
        );

        $this->addColumn(
            'url_key',
            [
                'header' => __('Url Key'),
                'index' => 'url_key',
            ]
        );

        $this->addColumn(
            'publish_date',
            [
                'header' => __('Publish Date'),
                'index' => 'publish_date',
                'type' => 'date',
            ]
        );

        $this->addColumn(
            'is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'options' => ['1' => 'Enabled', '2' => 'Disabled'],
            ]
        );

        return parent::_prepareColumns();
    }
}
