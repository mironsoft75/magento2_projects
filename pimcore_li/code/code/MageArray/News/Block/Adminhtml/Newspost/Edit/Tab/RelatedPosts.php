<?php

namespace MageArray\News\Block\Adminhtml\Newspost\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Framework\Registry;

/**
 * Class RelatedPosts
 * @package MageArray\News\Block\Adminhtml\Newspost\Edit\Tab
 */
class RelatedPosts extends Extended implements TabInterface
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
     * RelatedPosts constructor.
     * @param Context $context
     * @param Data $backendHelper
     * @param Status $status
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        Status $status,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_status = $status;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('related_posts_section');
        $this->setDefaultSort('newspost_id');
        $this->setUseAjax(true);
        if ($this->getPost() && $this->getPost()->getId()) {
            $this->setDefaultFilter(['in_posts' => 1]);
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
        return $this->_coreRegistry->registry('related_post_model');
    }

    /**
     * @param Column $column
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in post flag
        if ($column->getId() == 'in_posts') {
            $postIds = $this->_getSelectedPosts();
            if (empty($postIds)) {
                $postIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()
                    ->addFieldToFilter('newspost_id', ['in' => $postIds]);
            } else {
                if ($postIds) {
                    $this->getCollection()
                        ->addFieldToFilter('newspost_id', ['nin' => $postIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->getPost()->getCollection()->setPostFilter();
        $collection->addFieldToFilter(
            'newspost_id',
            ['neq' => $this->getPost()->getId()]
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
                'in_posts',
                [
                    'type' => 'checkbox',
                    'name' => 'in_posts',
                    'values' => $this->_getSelectedPosts(),
                    'align' => 'center',
                    'index' => 'newspost_id',
                    'header_css_class' => 'col-select',
                    'column_css_class' => 'col-select'
                ]
            );
        }

        $this->addColumn(
            'newspost_id_grid',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'newspost_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'title_grid',
            [
                'header' => __('Title'),
                'index' => 'title',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );

        $this->addColumn(
            'url_key_grid',
            [
                'header' => __('URL Key'),
                'index' => 'url_key',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );

        $this->addColumn(
            'is_active_grid',
            [
                'header' => __('Status'),
                'index' => 'is_active',
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
            'news/newspost/relatedPostsGrid',
            ['_current' => true]
        );
    }

    /**
     * @return mixed
     */
    protected function _getSelectedPosts()
    {
        $posts = $this->getPostsRelated();
        if (!is_array($posts)) {
            $posts = array_keys($this->getSelectedPosts());
        }
        return $posts;
    }

    /**
     * @return array
     */
    public function getSelectedPosts()
    {
        $posts = [];
        foreach ($this->_coreRegistry
                     ->registry('related_post_model')
                     ->getRelatedPosts() as $post) {
            $posts[$post->getId()] = ['position' => $post->getPosition()];
        }
        return $posts;
    }

    /**
     * @return mixed
     */
    public function getTabLabel()
    {
        return __('Related Posts');
    }

    /**
     * @return mixed
     */
    public function getTabTitle()
    {
        return __('Related Posts');
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
