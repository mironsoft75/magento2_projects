<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Tags\Edit\Tagged;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Catalog\Model\Product;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Grid extends Extended
{
    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory]
     */
    protected $_setsFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_visibility;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_type;
    /**
     * @var \Amasty\Blog\Model\Posts
     */
    private $posts;

    /**
     * @param \Magento\Backend\Block\Template\Context                                 $context
     * @param \Magento\Backend\Helper\Data                                            $backendHelper
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory                                   $productFactory
     * @param \Magento\Catalog\Model\Product\Type                                     $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status                  $status
     * @param \Magento\Catalog\Model\Product\Visibility                               $visibility
     * @param \Magento\Framework\Registry                                             $coreRegistry
     * @param array                                                                   $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Product\Type $type,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $status,
        \Magento\Catalog\Model\Product\Visibility $visibility,
        \Magento\Framework\Registry $coreRegistry,
        \Amasty\Blog\Model\Posts $posts,
        array $data = []
    ) {
        $this->_setsFactory = $setsFactory;
        $this->_productFactory = $productFactory;
        $this->_type = $type;
        $this->_status = $status;
        $this->_visibility = $visibility;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
        $this->posts = $posts;
    }

    /**
     * Set grid params
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blog_tagged_product_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    public function getPosts()
    {
        return $this->_coreRegistry->registry('amasty_blog_current_posts');
    }

    /**
     * Add filter
     *
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Prepare collection
     *
     * @return Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->_coreRegistry->registry('amasty_blog_current_posts');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Add columns to grid
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_posts',
            [
                'type' => 'checkbox',
                'name' => 'in_posts',
                'values' => $this->_getSelectedProducts(),
                'align' => 'center',
                'index' => 'post_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select'
            ]
        );

        $this->addColumn(
            'post_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'post_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );

        $this->addColumn(
            'url_key',
            [
                'header' => __('Url Key'),
                'index' => 'url_key',
                'header_css_class' => 'col-sku',
                'column_css_class' => 'col-sku'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Retrieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->_getData(
            'grid_url'
        ) ? $this->_getData(
            'grid_url'
        ) : $this->getUrl(
            'amasty_blog/tags/taggedGrid',
            ['_current' => true]
        );
    }

    protected function _getSelectedProducts()
    {
        $products = $this->getAllowedProducts();
        if (!is_array($products)) {
            $products = $this->getSelectedRuleProducts();
        }
        return $products;
    }

    public function getSelectedRuleProducts()
    {
        if (!$this->getPosts())
            return [];

        $ids = [];
        foreach ($this->getPosts() as $post) {
            $ids[] = $post->getId();
        }
        return $ids;
    }
}
