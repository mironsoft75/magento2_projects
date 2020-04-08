<?php

namespace MageArray\News\Block\Adminhtml\Newscat;

use MageArray\News\Model\NewscatFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Framework\Registry;

/**
 * Class Grid
 * @package MageArray\News\Block\Adminhtml\Newscat
 */
class Grid extends Extended
{
    /**
     * @var NewscatFactory
     */
    protected $_newscatFactory;

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Grid constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Data $backendHelper
     * @param NewscatFactory $newscatFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Data $backendHelper,
        NewscatFactory $newscatFactory,
        array $data = []
    ) {
        $this->_newscatFactory = $newscatFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('newsGrid');
        $this->setDefaultSort('cat_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_newscatFactory->create()->getCollection();
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
            'cat_id',
            [
                'header' => __('Category ID'),
                'type' => 'number',
                'index' => 'cat_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'cat_name',
            [
                'header' => __('Name'),
                'index' => 'cat_name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'cat_url_key',
            [
                'header' => __('URL Key'),
                'index' => 'cat_url_key',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'cat_status',
            [
                'header' => __('Status'),
                'index' => 'cat_status',
                'class' => 'xxx',
                'width' => '50px',
                'type' => 'options',
                'options' => ['1' => 'Enabled', '2' => 'Disabled'],
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
            'cat_parent',
            [
                'header' => __('Parent Category'),
                'index' => 'cat_parent',
                'renderer' => '\MageArray\News\Block\Adminhtml\Newscat\Grid\Renderer\Catname',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['cat_id' => $row->getId()]);
    }
}
