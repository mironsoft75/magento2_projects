<?php

namespace MageArray\News\Block\Adminhtml\Newscat\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

/**
 * Class Seo
 * @package MageArray\News\Block\Adminhtml\Newscat\Edit\Tab
 */
class Seo extends Generic implements
    TabInterface
{
    /**
     *
     */
    const FIELD_NAME_SUFFIX = 'newscat';
    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * Seo constructor.
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
            ->create(['data' => ['html_id_prefix' => 'page_seo_']]);

        $model = $this->_coreRegistry->registry('newscat');

        $fieldset = $form->addFieldset(
            'seo_fieldset',
            [
                'legend' => __('Search Engine Optimization')
            ]
        );

        $fieldset->addField(
            'cat_store_id',
            'multiselect',
            [
                'name' => 'cat_store_id[]',
                'label' => __('Store'),
                'title' => __('Store'),
                'values' => $this->_systemStore
                    ->getStoreValuesForForm(false, true)
            ]
        );

        $fieldset->addField(
            'cat_meta_title',
            'text',
            [
                'name' => 'cat_meta_title',
                'label' => __('Meta Title'),
                'title' => __('Meta Title')
            ]
        );

        $fieldset->addField(
            'cat_meta_keywords',
            'text',
            [
                'name' => 'cat_meta_keywords',
                'label' => __('Meta Keywords'),
                'title' => __('Meta Keywords')
            ]
        );

        $fieldset->addField(
            'cat_meta_description',
            'textarea',
            [
                'name' => 'cat_meta_description',
                'label' => __('Meta Description'),
                'title' => __('Meta Description')
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
