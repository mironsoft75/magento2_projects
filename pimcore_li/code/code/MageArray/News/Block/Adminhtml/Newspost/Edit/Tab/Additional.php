<?php

namespace MageArray\News\Block\Adminhtml\Newspost\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

/**
 * Class Additional
 * @package MageArray\News\Block\Adminhtml\Newspost\Edit\Tab
 */
class Additional extends Generic implements TabInterface
{
    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * Additional constructor.
     * @param Context $context
     * @param Store $systemStore
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Store $systemStore,
        Registry $registry,
        FormFactory $formFactory,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory
            ->create(['data' => ['html_id_prefix' => 'page_additional_']]);

        $model = $this->_coreRegistry->registry('newspost_post');

        $isElementDisabled = false;

        $fieldset = $form->addFieldset(
            'Additional_fieldset',
            [
                'legend' => __('Search Engine Optimization'),
                'class' => 'fieldset-wide',
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
            'store_id',
            'multiselect',
            [
                'name' => 'store_id[]',
                'label' => __('Store'),
                'title' => __('Store'),
                'values' => $this->_systemStore
                    ->getStoreValuesForForm(false, true),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'url_key',
            'text',
            [
                'name' => 'url_key',
                'label' => __('URL Key'),
                'title' => __('URL Key'),
                // 'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'meta_title',
            'text',
            [
                'name' => 'meta_title',
                'label' => __('Meta Title'),
                'title' => __('Meta Title'),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'meta_keywords',
            'text',
            [
                'name' => 'meta_keywords',
                'label' => __('Meta Keywords'),
                'title' => __('Meta Keywords'),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'meta_description',
            'textarea',
            [
                'name' => 'meta_description',
                'label' => __('Meta Description'),
                'title' => __('Meta Description'),
                'disabled' => $isElementDisabled
            ]
        );

        $this->_eventManager->
        dispatch(
            'adminhtml_news_edit_tab_additional_prepare_form',
            ['form' => $form]
        );

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getTabLabel()
    {
        return __('Search Engine Optimization');
    }

    /**
     * @return mixed
     */
    public function getTabTitle()
    {
        return __('Search Engine Optimization');
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

    /**
     * @param $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
