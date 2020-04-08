<?php
/** 
 * BSS Commerce Co. 
 * 
 * NOTICE OF LICENSE 
 * 
 * This source file is subject to the EULA 
 * that is bundled with this package in the file LICENSE.txt. 
 * It is also available through the world-wide-web at this URL: 
 * http://bsscommerce.com/Bss-Commerce-License.txt 
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE 
 * ================================================================= 
 * This package designed for Magento COMMUNITY edition 
 * BSS Commerce does not guarantee correct work of this extension 
 * on any other Magento edition except Magento COMMUNITY edition. 
 * BSS Commerce does not provide extension support in case of 
 * incorrect edition usage. 
 * ================================================================= 
 * 
 * @category   BSS 
 * @package    Bss_Gallery 
 * @author     Extension Team 
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com ) 
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt 
 */ 
namespace Bss\Gallery\Block\Adminhtml\Category\Edit\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;

class ListImage extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Core registry
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;
    protected $_collectionFactory;
    protected $_jsHelper;

    /**
     * Constructor
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Bss\Gallery\Model\ResourceModel\Item\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Bss\Gallery\Model\ResourceModel\Item\CollectionFactory $collectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Helper\Js $jsHelper,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_collectionFactory = $collectionFactory;
        $this->_jsHelper = $jsHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Initialize the item grid.
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('listImageGrid');
        $this->setSaveParametersInSession(true);
        $this->setDefaultLimit(20);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('id')) {
            $this->setDefaultFilter(array('in_images' => 1));
        }
    }

    public function getCategory()
    {
        return $this->getRequest()->getParam('category_id');
    }

    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create()->addFieldToSelect('*');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * add Column Filter To Collection
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_images') {
            $itemIds = $this->_getSelectedItems();

            if (empty($itemIds)) {
                $itemIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('item_id', array('in' => $itemIds));
            } else {
                if ($itemIds) {
                    $this->getCollection()->addFieldToFilter('item_id', array('nin' => $itemIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_images',
            [
                'type' => 'checkbox',
                'name' => 'in_images',
                'index' => 'item_id',
                'values' => $this->_getSelectedItems()
            ]
        );
        $this->addColumn(
            'item_id',
            [
                'header' => __('ID'),
                'index' => 'item_id',
            ]
        );
        $this->addColumn(
            'category_thumb',
            [
                'header' => __('Album Thumbnail'),
                'type' => 'radio',
                'html_name' => 'category_thumb',
                'index' => 'item_id',
                'filter' => false,
                'values' => $this->getSelectedThumb()
            ]
        );
        $this->addColumn(
            'item_image',
            [
                'header' => __('Thumbnail'),
                'index' => 'image',
                'renderer' => 'Bss\Gallery\Block\Adminhtml\Item\Grid\ImageRenderer',
                'filter' => false,
            ]
        );
        $this->addColumn(
            'item_title',
            [
                'header' => __('Title'),
                'index' => 'title',
            ]
        );
        $this->addColumn(
            'item_description',
            [
                'header' => __('Description'),
                'index' => 'description',
            ]
        );
        $this->addColumn(
            'item_is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'renderer' => 'Bss\Gallery\Block\Adminhtml\Item\Grid\StatusRenderer',
            ]
        );
        $this->addColumn(
            'sorting',
            [
                'header' => __('Order'),
                'name' => 'sorting',
                'index' => 'sorting',
                'width' => '50px',
                'editable' => true,
                'column_css_class' => 'no-display',
                'header_css_class' => 'no-display'

            ]
        );
        return parent::_prepareColumns();
    }

    public function getSelectedItems()
    {
        $category_id = $this->getRequest()->getParam('category_id');
        if (!isset($category_id)) {
            return [];
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $objectManager->create('Bss\Gallery\Model\Category');
        $category = $model->load($category_id);
        $itemIds = [];
        foreach (explode(',', trim($category->getData('Item_ids'))) as $itemId) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance()->create('Bss\Gallery\Model\Item');
            $item = $objectManager->load($itemId);
            $itemIds[$itemId] = ['sorting' => $item->getData('sorting')];
        }
        return $itemIds;
    }

    /**
     * @return array
     */
    protected function _getSelectedItems()
    {
        $items = $this->getRequest()->getParam('images');
        if (!is_array($items)) {
            $items = array_keys($this->getSelectedItems());
        }
        return $items;
    }

    public function getSelectedThumb()
    {
        $category_id = $this->getRequest()->getParam('category_id');
        if (!isset($category_id)) {
            return [];
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $session = $objectManager->create('Magento\Catalog\Model\Session');
        $thumb = $session->getCategoryThumb();
        $keys = $session->getKeySession();
        if ($thumb && $keys && $thumb['keys'] == $keys) {
            return array($thumb['id']);
        } else {
            $model = $objectManager->create('Bss\Gallery\Model\Category');
            $category = $model->load($category_id);
            return array($category->getItemThumbId());
        }
    }

    public function getGridUrl()
    {
        return $this->getUrl('gallery/*/listimagegrid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return '';
    }
}

