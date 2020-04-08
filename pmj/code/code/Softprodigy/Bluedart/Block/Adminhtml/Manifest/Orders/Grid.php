<?php

namespace Softprodigy\Bluedart\Block\Adminhtml\Manifest\Orders;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Softprodigy\Bluedart\Model\ResourceModel\Awblist\CollectionFactory AS orderCollection;
 
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
            array $data = []
    ) {
        
        //var_dump($collectionFactory); exit;
        $this->_collectionFactory = $collectionFactory;
        $this->_resourceModel = $_resouceModel;
        $this->pageLayoutBuilder = $pageLayoutBuilder;
        $this->_dealHelper = $dealHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        
        parent::_construct();
         
        $this->setId('spsBluedartManifestGrid');
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
        
        $collection = $this->_collectionFactory->create();
        $collection->getSelect()->order('id DESC');
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
                'awb_number', 
                [
                'header' => __('Awb No.'),
                'index' => 'awb_number',
                ]
        );
        $this->addColumn(
                'order_increment_id', 
                [
                'header' => __('Order Id'),
                'index' => 'order_increment_id',
                ]
        );
        $this->addColumn(
                'city_state', 
                [
                'header' => __('City/State'),
                'index' => 'city_state',
                ]
        );
        $this->addColumn(
                'product_details', 
                [
                'header' => __('Product Details'),
                'index' => 'product_details',
                ]
        );
        $this->addColumn(
                'awb_weight', 
                [
                'header' => __('Atual Weight (in Kg)'),
                'index' => 'awb_weight',
                ]
        );
        

        $this->addColumn(
                'awb_date', [
                    'header' => __('Waybill Date'),
                    'index' => 'awb_date',
                    'type' => 'datetime',
                    'header_css_class' => 'col-date',
                    'column_css_class' => 'col-date'
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
     * Row click url
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row) {
         return false;
    }

}
