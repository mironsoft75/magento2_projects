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

class Statistics extends Generic implements TabInterface
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
        return __('Statistics');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Statistics');
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
        $form->setHtmlIdPrefix('statistic_');

        $fieldset = $form->addFieldset('meta_fieldset', ['legend' => __('Statistics')]);

        $value = $model->getViews();

        $fieldset->addField(
            'view',
            'label',
            [
                'name' => 'view',
                'label' => __('Views'),
                'title' => __('Views'),
                'value' => $value
            ]
        );
        
        $form->addValues(['value' => $value]);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}