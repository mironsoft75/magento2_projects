<?php

namespace Softprodigy\Bluedart\Block\Adminhtml\Manifest\History;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Softprodigy\Bluedart\Model\ResourceModel\Manifest\CollectionFactory AS manifestCollection;
 
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
    protected $_dealHelper;

    
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
             manifestCollection $collectionFactory, 
            \Softprodigy\Bluedart\Helper\Data $dealHelper, 
            array $data = []
    ) {
        
        //var_dump($collectionFactory); exit;
        $this->_collectionFactory = $collectionFactory;
          
        $this->_dealHelper = $dealHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        
        parent::_construct();
         
        $this->setId('spsBluedartManifestHistGrid');
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
                'row_id', 
                [
                'header' => __('Id'),
                'index' => 'id',
                ]
        );
        $this->addColumn(
                'id', 
                [
                'header' => __('Order Count'),
                'index' => 'order_count',
                ]
        );
        $this->addColumn(
                'batch_number', 
                [
                'header' => __('Batch Number'),
                'index' => 'batch_number',
                ]
        );
        $this->addColumn(
                'gen_from', [
                    'header' => __('From Date'),
                    'index' => 'gen_from',
                    'type' => 'datetime',
                    'header_css_class' => 'col-date',
                    'column_css_class' => 'col-date'
                ]
        );
        $this->addColumn(
                'gen_to', [
                    'header' => __('To Date'),
                    'index' => 'gen_to',
                    'type' => 'datetime',
                    'header_css_class' => 'col-date',
                    'column_css_class' => 'col-date'
                ]
        );
        
        $this->addColumn(
                'file_name', 
                [
                'header' => __('Download'),
                'index' => 'file_name',
                'renderer' => 'Softprodigy\Bluedart\Block\Adminhtml\Manifest\History\Renderer\Link'
                ]
        );
      

        $this->addColumn(
                'created_on', [
                    'header' => __('Created On'),
                    'index' => 'created_on',
                    'type' => 'datetime',
                    'header_css_class' => 'col-date',
                    'column_css_class' => 'col-date'
                ]
        );
          
        return parent::_prepareColumns();
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
