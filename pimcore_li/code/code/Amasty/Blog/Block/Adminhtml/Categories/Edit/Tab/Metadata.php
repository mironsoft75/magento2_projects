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

class Metadata extends Generic implements TabInterface
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
        return __('Meta Data');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Meta Data');
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
        $form->setHtmlIdPrefix('smetadata_');

        $fieldset = $form->addFieldset('meta_fieldset', ['legend' => __('Meta Data')]);

        $fieldset->addField(
            'meta_title',
            'text',
            ['name' => 'meta_title', 'label' => __('Meta Title'), 'title' => __('Meta Title')]
        );

        $fieldset->addField(
            'meta_tags',
            'text',
            ['name' => 'meta_tags', 'label' => __('Meta Tags'), 'title' => __('Meta Tags')]
        );

        $fieldset->addField(
            'meta_description',
            'text',
            ['name' => 'meta_description', 'label' => __('Meta Description'), 'title' => __('Meta Description')]
        );

        $form->setValues($model->getData());
        $form->addValues(['id'=>$model->getId()]);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}