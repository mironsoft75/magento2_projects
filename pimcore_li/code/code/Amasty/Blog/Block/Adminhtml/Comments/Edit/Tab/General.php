<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Comments\Edit\Tab;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject as ObjectConverter;

class General extends Generic implements TabInterface
{
    
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
     * @var \Magento\Store\Model\System\Store
     */
    private $store;
    /**
     * @var \Magento\Backend\Block\Template\Context
     */
    private $context;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $config,
        \Magento\Store\Model\System\Store $store,
        array $data
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->config = $config;
        $this->store = $store;
        $this->context = $context;
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
        $model = $this->_coreRegistry->registry('current_amasty_blog_comment');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('comments_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Content')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
        }

        if ($model->getReplyTo()) {
            $fieldset->addField('reply_to', 'hidden', ['name' => 'reply_to']);
        }

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Name'), 'title' => __('Name'), 'required' => true]
        );

        $fieldset->addField(
            'email',
            'text',
            ['name' => 'email', 'label' => __('E-Mail'), 'title' => __('E-Mail'), 'required' => false]
        );

        $fieldset->addField(
            'message',
            'editor',
            ['name' => 'message', 'label' => __('Comment'), 'title' => __('Comment'), 'required' => false]
        );

        $fieldset->addField('status', 'select', [
            'label'  => __('Status'),
            'id'     => 'status',
            'name'   => 'status',
            'values' => [
                \Amasty\Blog\Model\Comments::STATUS_APPROVED => __('Approved'),
                \Amasty\Blog\Model\Comments::STATUS_PENDING  => __('Pending'),
                \Amasty\Blog\Model\Comments::STATUS_REJECTED => __('Rejected')
            ]
        ]);

        if (!$this->context->getStoreManager()->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'select',
                [
                    'name' => 'store_id',
                    'label' => __('Posted From'),
                    'title' => __('Posted From'),
                    'required' => true,
                    'values' => $this->store->getStoreValuesForForm(false, false)
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