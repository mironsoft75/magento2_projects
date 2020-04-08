<?php

namespace MageArray\News\Block\Adminhtml\Newscomment;

use MageArray\News\Helper\Data as NewsHelper;
use MageArray\News\Model\Commentstatus;
use MageArray\News\Model\NewscommentFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Framework\Registry;

/**
 * Class Grid
 * @package MageArray\News\Block\Adminhtml\Newscomment
 */
class Grid extends Extended
{
    /**
     * @var NewscommentFactory
     */
    protected $_newscommentFactory;

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var
     */
    protected $_categories;
    /**
     * @var Commentstatus
     */
    protected $_status;
    /**
     * @var NewsHelper
     */
    protected $_helper;

    /**
     * Grid constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Data $backendHelper
     * @param NewscommentFactory $newscommentFactory
     * @param Commentstatus $status
     * @param NewsHelper $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Data $backendHelper,
        NewscommentFactory $newscommentFactory,
        Commentstatus $status,
        NewsHelper $helper,
        array $data = []
    ) {
        $this->_newscommentFactory = $newscommentFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_status = $status;
        $this->_helper = $helper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('newscommentGrid');
        $this->setDefaultSort('comment_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_newscommentFactory->create()->getCollection();
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
            'comment_id',
            [
                'header' => __('Comment ID'),
                'type' => 'number',
                'index' => 'comment_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'newspost_id',
            [
                'header' => __('News Post Title'),
                'index' => 'newspost_id',
                'renderer' => '\MageArray\News\Block\Adminhtml\Newscomment\Grid\Renderer\Postname',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'comment',
            [
                'header' => __('Comment'),
                'index' => 'comment',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'sender_name',
            [
                'header' => __('Sender Name'),
                'index' => 'sender_name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'sender_email',
            [
                'header' => __('Sender Email'),
                'index' => 'sender_email',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'commented_date',
            [
                'header' => __('Commented On'),
                'index' => 'commented_date',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'comment_status',
            [
                'header' => __('Status'),
                'index' => 'comment_status',
                'type' => 'options',
                'class' => 'xxx',
                'width' => '50px',
                'options' => ['1' => 'Approved', '2' => 'Disapproved']
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('comment_id');
        $this->getMassactionBlock()->setFormFieldName('comment_id');
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
