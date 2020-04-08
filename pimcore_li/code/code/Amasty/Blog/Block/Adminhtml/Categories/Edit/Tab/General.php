<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Categories\Edit\Tab;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject as ObjectConverter;

class General extends Generic implements TabInterface
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
        return __('General');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('General');
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
        $model = $this->_coreRegistry->registry('current_amasty_blog_category');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('additional_');

        $fieldset = $form->addFieldset('general_fieldset', ['legend' => __('General')]);

        if ($model->getCategoryId()) {
            $fieldset->addField('category_id', 'hidden', ['name' => 'category_id']);
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'url_key',
            'text',
            [
                'name' => 'url_key',
                'class'=>'validate-identifier',
                'label' => __('Url Key'),
                'title' => __('Url Key'),
                //'required' => true
            ]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            ['name' => 'sort_order', 'label' => __('Sort Order'), 'title' => __('Sort Order')]
        );

        $fieldset->addField('status', 'select', [
            'label'  => __('Status'),
            'id'     => 'status',
            'name'   => 'status',
            'values' => [
                '1' => __('Enabled'),
                '0' => __('Disabled')
            ]
        ]);

        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_ids',
                'multiselect',
                [
                    'name' => 'store_ids[]',
                    'label' => __('Visible In'),
                    'title' => __('Visible In'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, false)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        }

        $form->setValues($model->getData());
        $form->addValues(['id'=>$model->getId()]);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}