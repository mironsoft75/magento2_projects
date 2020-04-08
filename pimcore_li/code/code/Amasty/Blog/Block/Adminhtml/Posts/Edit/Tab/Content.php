<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Block\Adminhtml\Posts\Edit\Tab;

use Amasty\Blog\Helper\Posts;
use Amasty\Blog\Model\Posts as BlogPosts;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject as ObjectConverter;

class Content extends Generic implements TabInterface
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory
     */
    protected $dependencyFieldFactory;

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
     * Content constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $config
     * @param \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $dependencyFieldFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $dependencyFieldFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $config,
        array $data
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->dependencyFieldFactory = $dependencyFieldFactory;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Content');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Content');
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

        /** @var \Magento\Backend\Block\Text\ListText $formAfter */
        $formAfter = $this->getChildBlock('form_after');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('posts_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Content')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        //load dependence tables
        $model->getCategories();
        $model->getStores();

        $fieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
        );

        $fieldset->addField(
            'url_key',
            'text',
            ['name' => 'url_key', 'label' => __('URL Key'), 'title' => __('URL Key')]
        );

        $short = $fieldset->addField(
            'short_content',
            'editor',
            [
                'label'    => __('Short Content'),
                'config'    => $this->config->getConfig(),
                'name'     => 'short_content',
            ]
        );

        $fieldset->addField(
            'full_content',
            'editor',
            [
                'label'    => __('Full Content'),
                'required' => true,
                'config'    => $this->config->getConfig(),
                'name'     => 'full_content',
            ]
        );

        $tags = $model->getTags();
        $fieldset->addField(
            'tags',
            'text',
            [
                'name' => 'tags',
                'label' => __('Tags'),
                'title' => __('Tags'),
                'required' => false,
                'value' => $tags,
            ]
        );

        $publishFieldset = $form->addFieldset('publish_fieldset', ['legend' => __('Publish Status')]);

        $status = $publishFieldset->addField('status', 'select', [
            'label'  => __('Status'),
            'id'     => 'status',
            'name'   => 'status',
            'values' => [
                BlogPosts::STATUS_ENABLED   => __('Published'),
                BlogPosts::STATUS_DISABLED  => __('Disabled'),
                BlogPosts::STATUS_HIDDEN    => __('Hidden'),
                BlogPosts::STATUS_SCHEDULED => __('Scheduled'),
            ]
        ]);

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::MEDIUM);
        $publishDate = $publishFieldset->addField(
            'published_at',
            'date',
            [
                'name' => 'published_at',
                'label' => __('Published Date'),
                'title' => __('Published Date'),
                'input_format' => $dateFormat,
                'date_format' => $dateFormat
            ]
        );

        $displayPublishedDateStatuses = [
            BlogPosts::STATUS_ENABLED,
            BlogPosts::STATUS_SCHEDULED
        ];

        $blockPublish = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Form\Element\Dependence'
        )
            ->addFieldMap($status->getHtmlId(), $status->getName())
            ->addFieldMap($publishDate->getHtmlId(), $publishDate->getName())
            ->addFieldDependence(
                $publishDate->getName(),
                $status->getName(),
                $this->dependencyFieldFactory->create(
                    [
                        'fieldData' => [
                            'separator' => ';',
                            'value' => implode(";", $displayPublishedDateStatuses),
                            'negative' => false
                        ],
                        'fieldPrefix' => ''
                    ]
                )
            );

        $formAfter->setChild(
            'publish_dependence',
            $blockPublish
        );

        $form->setValues($model->getData());
        $form->addValues(['id'=>$model->getId()]);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
