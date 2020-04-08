<?php

namespace MageArray\News\Block\Adminhtml\Newspost\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\WebsiteFactory;

/**
 * Class RelatedProducts
 * @package MageArray\News\Block\Adminhtml\Newspost\Edit\Tab
 */
class RelatedProducts extends Extended implements TabInterface
{
    /**
     * @var Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var Status
     */
    protected $_status;

    /**
     * @var CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var Visibility
     */
    protected $_visibility;

    /**
     * @var WebsiteFactory
     */
    protected $_websiteFactory;

    /**
     * RelatedProducts constructor.
     * @param Context $context
     * @param Data $backendHelper
     * @param Status $status
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $visibility
     * @param WebsiteFactory $websiteFactory
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        Status $status,
        CollectionFactory $productCollectionFactory,
        Visibility $visibility,
        WebsiteFactory $websiteFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_status = $status;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_visibility = $visibility;
        $this->_websiteFactory = $websiteFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('related_products_section');
        $this->setDefaultSort('newspost_id');
        $this->setUseAjax(true);
        if ($this->getPost() && $this->getPost()->getId()) {
            $this->setDefaultFilter(['in_products' => 1]);
        }
        if ($this->isReadonly()) {
            $this->setFilterVisibility(false);
        }
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->_coreRegistry->registry('related_pro_model');
    }

    /**
     * @param Column $column
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in post flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()
                    ->addFieldToFilter('entity_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()
                        ->addFieldToFilter('entity_id', ['nin' => $productIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareCollection()
    {
        $collection = $this->_productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->joinField(
                'websites',
                'catalog_product_website',
                'website_id',
                'product_id=entity_id',
                null,
                'left'
            );

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return bool
     */
    public function isReadonly()
    {
        return false;
    }

    /**
     * @return $this
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareColumns()
    {
        if (!$this->isReadonly()) {
            $this->addColumn(
                'in_products',
                [
                    'type' => 'checkbox',
                    'name' => 'in_products',
                    'values' => $this->_getSelectedProducts(),
                    'align' => 'center',
                    'index' => 'entity_id',
                    'header_css_class' => 'col-select',
                    'column_css_class' => 'col-select'
                ]
            );
        }

        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'index' => 'name',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );

        $this->addColumn(
            'sku',
            [
                'header' => __('SKU'),
                'index' => 'sku',
                'header_css_class' => 'col-sku',
                'column_css_class' => 'col-sku'
            ]
        );

        $this->addColumn(
            'visibility',
            [
                'header' => __('Visibility'),
                'index' => 'visibility',
                'type' => 'options',
                'options' => $this->_visibility->getOptionArray(),
                'header_css_class' => 'col-visibility',
                'column_css_class' => 'col-visibility'
            ]
        );

        if (!$this->_storeManager->isSingleStoreMode()) {
            $this->addColumn(
                'websites',
                [
                    'header' => __('Websites'),
                    'sortable' => false,
                    'index' => 'websites',
                    'type' => 'options',
                    'options' => $this->_websiteFactory
                        ->create()->getCollection()->toOptionHash(),
                    'header_css_class' => 'col-websites',
                    'column_css_class' => 'col-websites'
                ]
            );
        }

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => $this->_status->getOptionArray(),
                'header_css_class' => 'col-status',
                'column_css_class' => 'col-status',
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
            'position',
            [
                'header' => __('Position'),
                'name' => 'position',
                'type' => 'number',
                'validate_class' => 'validate-number',
                'index' => 'position',
                'editable' => true,
                'edit_only' => false,
                'sortable' => false,
                'filter' => false,
                'header_css_class' => 'col-position',
                'column_css_class' => 'col-position'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return mixed|string
     */
    public function getGridUrl()
    {
        return $this->getData(
            'grid_url'
        ) ? $this->getData(
            'grid_url'
        ) : $this->getUrl(
            'news/newspost/relatedProductsGrid',
            ['_current' => true]
        );
    }

    /**
     * @return mixed
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getProductsRelated();
        if (!is_array($products)) {
            $products = array_keys($this->getSelectedProducts());
        }
        return $products;
    }

    /**
     * @return array
     */
    public function getSelectedProducts()
    {
        $products = [];
        foreach ($this->_coreRegistry
                     ->registry('related_pro_model')
                     ->getRelatedProducts() as $post) {
            $products[$post->getId()] = ['position' => $post->getPosition()];
        }
        return $products;
    }

    /**
     * @return mixed
     */
    public function getTabLabel()
    {
        return __('Related Products');
    }

    /**
     * @return mixed
     */
    public function getTabTitle()
    {
        return __('Related Products');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
