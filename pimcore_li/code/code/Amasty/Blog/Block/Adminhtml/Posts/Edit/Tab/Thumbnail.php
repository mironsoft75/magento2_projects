<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Posts\Edit\Tab;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject as ObjectConverter;

class Thumbnail extends Generic implements TabInterface
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Convert\DataObject
     */
    protected $_objectConverter;
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    private $config;
    /**
     * @var \Amasty\Blog\Helper\Data
     */
    private $dataHelper;

    /**
     * General constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param \Magento\Store\Model\System\Store       $systemStore
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ObjectConverter $objectConverter,
        GroupRepositoryInterface $groupRepository,
        \Magento\Cms\Model\Wysiwyg\Config $config,
        \Amasty\Blog\Helper\Data $dataHelper,
        array $data
    ) {
        $this->_systemStore = $systemStore;
        $this->groupRepository = $groupRepository;
        $this->_objectConverter = $objectConverter;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context, $registry, $formFactory, $data);
        $this->config = $config;
        $this->dataHelper = $dataHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Thumbnail');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Thumbnail');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_amasty_blog_post');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('thumbnail_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Thumbnails')]);

        $fieldset->addField('post_thumbnail', 'file', array(
            'label'     => __('Post Image'),
            'name'      => 'post_thumbnail',
            'note' => __('The image will be resized to size from blog config'),
            'after_element_html' => $this->getImageHtml('post_thumbnail', $model->getPostThumbnail()),
        ));

        $fieldset->addField('list_thumbnail', 'file', array(
            'label'     => __('List Image'),
            'name'      => 'list_thumbnail',
            'note' => __('The image will be resized to size from blog config'),
            'after_element_html' => $this->getImageHtml('list_thumbnail', $model->getListThumbnail()),
        ));

        $fieldset->addField(
            'thumbnail_url',
            'text',
            ['name' => 'thumbnail_url', 'label' => __('Image link'), 'title' => __('Image link'), 'required' => false]
        );
/*
        $publishFieldset = $form->addFieldset('display_fieldset', ['legend' => __('Display settings')]);
        
        $publishFieldset->addField(
            'grid_class',
            'radios',
            [
                'label' => __('Grid width'),
                'title' => __('Grid width'),
                'name' => 'grid_class',
                'required' => true,
                'class' => 'blog_radio',
                'values' => [
                    [
                        'value'=>'w1',
                        'label'=>__('Normal')
                    ],
                    [
                        'value'=>'w2',
                        'label'=>__('Middle')
                    ],
                    [
                        'value'=>'w3',
                        'label'=>__('Wide')
                    ],
                ]
            ]
        );
        */
        $form->setValues($model->getData());
        $form->addValues(['id'=>$model->getId()]);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    protected function getImageHtml($field, $img)
    {
        $html = '';
        if ($img) {
            $html .= '<p style="margin-top: 5px">';
            $html .= '<img style="max-width:300px" src="' . $this->dataHelper->getImageUrl($img) . '" />';
            $html .= '<br/><input type="checkbox" value="1" name="remove_' . $field . '"/> ' . __('Remove');
            $html .= '<input type="hidden" value="' . $img . '" name="old_' . $field . '"/>';
            $html .= '</p>';
        }
        return $html;
    }
}