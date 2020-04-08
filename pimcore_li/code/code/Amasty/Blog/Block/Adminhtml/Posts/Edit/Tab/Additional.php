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

class Additional extends Generic implements TabInterface
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
     * @var \Amasty\Blog\Model\ResourceModel\Categories\Collection
     */
    private $categoriesCollection;
    
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
        \Amasty\Blog\Model\ResourceModel\Categories\Collection $categoriesCollection,
        array $data
    ) {
        $this->_systemStore = $systemStore;
        $this->groupRepository = $groupRepository;
        $this->_objectConverter = $objectConverter;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context, $registry, $formFactory, $data);
        $this->config = $config;
        $this->dataHelper = $dataHelper;
        $this->categoriesCollection = $categoriesCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Additional');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Additional');
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
        $form->setHtmlIdPrefix('additional_');

        $fieldset = $form->addFieldset('author_fieldset', ['legend' => __('Author')]);


        $fieldset->addField(
            'posted_by',
            'text',
            ['name' => 'posted_by', 'label' => __('Name'), 'title' => __('Name')]
        );

        $fieldset->addField(
            'google_profile',
            'text',
            ['name' => 'google_profile', 'label' => __('Google Profile'), 'title' => __('Google Profile')]
        );

        $fieldset->addField(
            'facebook_profile',
            'text',
            ['name' => 'facebook_profile', 'label' => __('Facebook Profile'), 'title' => __('Facebook Profile')]
        );

        $fieldset->addField(
            'twitter_profile',
            'text',
            ['name' => 'twitter_profile', 'label' => __('Twitter Profile'), 'title' => __('Twitter Profile')]
        );

        $publishFieldset = $form->addFieldset('additional_fieldset', ['legend' => __('Additional')]);

        $categories = $this->categoriesCollection->toOptionArray();
        
        $publishFieldset->addField(
            'categories',
            'multiselect',
            [
                'name' => 'categories',
                'label' => __('Posted in'),
                'title' => __('Posted in'),
                'required' => false,
                'values' =>  $categories
            ]
        );
        
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'stores',
                'multiselect',
                [
                    'name' => 'stores',
                    'label' => __('Posted From'),
                    'title' => __('Posted From'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, false)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        }

        $publishFieldset->addField('comments_enabled', 'select', [
            'label'  => __('Allow comments'),
            'id'     => 'comments_enabled',
            'name'   => 'comments_enabled',
            'values' => [
                '1' => __('Yes'),
                '0' => __('No')
            ]
        ]);

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