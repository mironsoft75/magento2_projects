<?php

namespace Softprodigy\Bluedart\Block\Adminhtml\Mass\Orders;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory AS orderCollection;
use Magento\Sales\Model\Order\Address;
/**
 * Description of Grid
 *
 * @author mannu
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended {

    /**
     *
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory 
     */
    protected $_collectionFactory;
    protected $_objectManager;
    protected $_dealHelper;

    /**
     * @var \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    protected $pageLayoutBuilder;
    protected $_resourceModel;
    protected $awbmodel;
    protected $orderAddr;
    /**
     * 
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param orderCollection $collectionFactory
     * @param \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder
     * @param \Magento\Framework\App\ResourceConnection $_resouceModel
     * @param \Softprodigy\Bluedart\Helper\Data $dealHelper
     * @param array $data
     */
    public function __construct(
            \Magento\Backend\Block\Template\Context $context, 
            \Magento\Backend\Helper\Data $backendHelper, 
            orderCollection $collectionFactory, 
            \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder, 
            \Magento\Framework\App\ResourceConnection $_resouceModel, 
            \Softprodigy\Bluedart\Helper\Data $dealHelper, 
            \Softprodigy\Bluedart\Model\Awblist $awblist,
            Address $orderAddr,
            array $data = []
    ) {
        
        //var_dump($collectionFactory); exit;
        $this->_collectionFactory = $collectionFactory;
        $this->_resourceModel = $_resouceModel;
        $this->pageLayoutBuilder = $pageLayoutBuilder;
        $this->_dealHelper = $dealHelper;
        $this->awbmodel = $awblist;
        $this->orderAddr = $orderAddr;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        
        parent::_construct();
         
        $this->setId('spsOrdersGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(false);
        //$this->setUseAjax(true);
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection() { 
	   
        //$resource = $this->_resourceModel;
        $awmcoll = $this->awbmodel->getCollection();
        $awmcoll->distinct(true);
        $awmcoll->addFieldToSelect('order_id');
          
        $inQuery = [];
          
        $inQuery = $awmcoll->getColumnValues('order_id');
        //var_dump($inQuery); die;
        $ordeAddrc = $this->orderAddr->getCollection();
        $ordeAddrc->distinct(true);
        $ordeAddrc->addFieldToSelect('parent_id');
        
        if(!empty($inQuery))
			$ordeAddrc->addFieldToFilter('parent_id', ['nin'=>$inQuery]);
			
        $ordeAddrc->addFieldToFilter('address_type', Address::TYPE_SHIPPING);
        $ordeAddrc->addFieldToFilter('country_id', 'IN');
        
        $ninQuery = $ordeAddrc->getColumnValues('parent_id');
        
        $collection = $this->_collectionFactory->create();
        
        if(!empty($inQuery))
			$collection->addFieldToFilter('entity_id', ['in'=>$ninQuery]);
			
        $collection->getSelect()->order('entity_id DESC');
        
        $this->setCollection($collection);
        
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns() {
        //$this->addColumn('title', ['header' => __('Title'), 'index' => 'title']);
        //$this->addColumn('identifier', ['header' => __('URL Key'), 'index' => 'identifier']);
        
        $this->addColumn(
                'increment_id', 
                [
                'header' => __('Order Id'),
                'index' => 'increment_id',
                ]
        );


        $this->addColumn(
                'created_at', [
            'header' => __('Purchase Date'),
            'index' => 'created_at',
            'type' => 'datetime',
            'header_css_class' => 'col-date',
            'column_css_class' => 'col-date'
                ]
        );
        
        $this->addColumn(
            'store_id',
            [
                'header' => __('Purchase Point'),
                'index' => 'store_id',
                'type' => 'store',
                'store_all' => true,
                'store_view' => true,
                'sortable' => false,
                'skipEmptyStoresLabel' => true,
                'filter_condition_callback' => [$this, '_filterStoreCondition']
            ]
        );
        
        
        
        
        $this->addColumn(
                'base_grand_total', [
            'header' => __('Grand Total (Base)'),
            'index' => 'base_grand_total',
                //'type' => 'text',
                ]
        );
        
        $this->addColumn(
                'grand_total', [
                    'header' => __('Grand Total (Purchased)'),
                    'index' => 'grand_total',
                //'type' => 'text',
                ]
        );
        
        $this->addColumn(
            'view',
            [
                'header' => __('view'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => 'sales/order/view'
                        ],
                        'field' => 'order_id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
        
        return parent::_prepareColumns();
    }

    /**
     * After load collection
     *
     * @return void
     */
    protected function _afterLoadCollection() {
        // $this->getCollection()->walk('afterLoad');
        // parent::_afterLoadCollection();
    }
      /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        return parent::_addColumnFilterToCollection($column);
    }
    /**
     * Filter store condition
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @param \Magento\Framework\DataObject $column
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _filterStoreCondition($collection, \Magento\Framework\DataObject $column)
    {
        if (!($value = $column->getFilter()->getValue())) {
            return;
        }

        $this->getCollection()->getSelect()->where('store_id='.$value);
    }
    
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('orders[]');
        //$this->getMassactionBlock()->setSelected();
        $this->getMassactionBlock()->addItem(
            '-',
            [
                'label' => __('#'),
                'url' => '#',
            ]
        );
        return $this;
    }
    /**
     * Row click url
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row) {
         return false;
    }

}
