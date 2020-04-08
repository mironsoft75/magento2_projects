<?php

namespace MageArray\News\Block\Adminhtml\Newspost;

use MageArray\News\Model\NewspostFactory;
use MageArray\News\Model\Status;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Framework\Registry;

/**
 * Class Grid
 * @package MageArray\News\Block\Adminhtml\Newspost
 */
class Grid extends Extended
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
     * Grid constructor.
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
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'newspost_id',
            [
                'header' => __('Post ID'),
                'type' => 'number',
                'index' => 'newspost_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'publish_date',
            [
                'header' => __('Publish Date'),
                'index' => 'publish_date',
                'type' => 'date',
                'class' => 'xxx',
                'width' => '50px',
				'renderer'  => 'MageArray\News\Block\Adminhtml\Newspost\Renderer\PublishDate',
            ]
        );
        $this->addColumn(
            'is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'options' => ['1' => 'Enabled', '2' => 'Disabled'],
                'class' => 'xxx',
                'width' => '50px',
                'frame_callback' => [
                    $this->getLayout()
                        ->createBlock(
                            'MageArray\News\Block\Adminhtml\Grid\Column\Statuses'
                        ),
                    'decorateStatus'
                ],
            ]
        );

        $this->addColumn(
            'action',
            [
                'header' => __('Action'),
                'index' => 'is_active',
                'type' => 'action',
                'getter' => 'getId',
                'class' => 'xxx',
                'width' => '20px',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit',
                        ],
                        'field' => 'newspost_id'
                    ]
                ],
                'filter' => false,
                'sortable' => false
            ]
        );

        $this->addExportType(
            '*/*/exportCsv',
            __('CSV')
        );
        return parent::_prepareColumns();
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['newspost_id' => $row->getId()]);
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('newspost');
        $this->getMassactionBlock()->addItem('delete', [
            'label' => __('Delete'),
            'url' => $this->getUrl('*/*/massDelete', ['' => '']),
            'confirm' => __('Are you sure?')
        ]);

        $statuses = $this->_status->getOptionArray();

        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem('status', [
            'label' => __('Change status'),
            'url' => $this->getUrl('*/*/massStatus', ['_current' => true]),
            'additional' => [
                'visibility' => [
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => __('Status'),
                    'values' => $statuses
                ]
            ]
        ]);

        return $this;
    }
}
