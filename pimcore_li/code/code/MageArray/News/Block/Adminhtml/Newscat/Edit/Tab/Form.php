<?php

namespace MageArray\News\Block\Adminhtml\Newscat\Edit\Tab;

use MageArray\News\Helper\Data;
use MageArray\News\Model\Categories;
use MageArray\News\Model\Status;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

/**
 * Class Form
 * @package MageArray\News\Block\Adminhtml\Newscat\Edit\Tab
 */
class Form extends Generic implements TabInterface
{

    /**
     *
     */
    const FIELD_NAME_SUFFIX = 'newscat';
    /**
     * @var FieldFactory
     */
    protected $_fieldFactory;
    /**
     * @var Store
     */
    protected $_systemStore;
    /**
     * @var Data
     */
    protected $_newscatHelper;
    /**
     * @var Status
     */
    protected $_status;
    /**
     * @var Categories
     */
    protected $_categories;

    /**
     * Form constructor.
     * @param Context $context
     * @param Data $newscatHelper
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param FieldFactory $fieldFactory
     * @param Status $status
     * @param Categories $categories
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $newscatHelper,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        FieldFactory $fieldFactory,
        Status $status,
        Categories $categories,
        array $data = []
    ) {
        $this->_localeDate = $context->getLocaleDate();
        $this->_systemStore = $systemStore;
        $this->_newscatHelper = $newscatHelper;
        $this->_fieldFactory = $fieldFactory;
        $this->_status = $status;
        $this->_categories = $categories;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('page.title')
            ->setPageTitle($this->getPageTitle());
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->getNewscat();
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Category Information')]
        );

        if ($model->getId()) {
            $fieldset->addField('cat_id', 'hidden', ['name' => 'cat_id']);
        }

        $fieldset->addField(
            'cat_name',
            'text',
            [
                'name' => 'cat_name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
                'class' => 'required-entry',
            ]
        );

        $fieldset->addField(
            'cat_url_key',
            'text',
            [
                'name' => 'cat_url_key',
                'label' => __('URL Key'),
                'title' => __('URL Key'),
            ]
        );

        $fieldset->addField(
            'cat_status',
            'select',
            [
                'name' => 'cat_status',
                'label' => __('Status'),
                'title' => __('Status'),
                'required' => true,
                'options' => $this->_status->getOptionArray(),
                'class' => 'required-entry',
            ]
        );

        $fieldset->addField(
            'cat_parent',
            'select',
            [
                'name' => 'cat_parent',
                'label' => __('Parent Category'),
                'title' => __('Parent Category'),
                'required' => true,
                'class' => 'required-entry',
                'options' => $this->_categories->getOptionArraytwo()
            ]
        );

        $form->setValues($model->getData());
        $form->addFieldNameSuffix(self::FIELD_NAME_SUFFIX);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getNewscat()
    {
        return $this->_coreRegistry->registry('newscat');
    }

    /**
     * @return mixed
     */
    public function getPageTitle()
    {
        return $this->getNewscat()
            ->getId() ? __(
                "Edit Category '%1'",
            $this->escapeHtml($this->getNewscat()
                ->getCatName())
            ) : __('New Category');
    }

    /**
     * @return mixed
     */
    public function getTabLabel()
    {
        return __('Category Information');
    }

    /**
     * @return mixed
     */
    public function getTabTitle()
    {
        return __('Category Information');
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
