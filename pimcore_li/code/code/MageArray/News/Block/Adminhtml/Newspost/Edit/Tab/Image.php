<?php

namespace MageArray\News\Block\Adminhtml\Newspost\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Class Image
 * @package MageArray\News\Block\Adminhtml\Newspost\Edit\Tab
 */
class Image extends Generic implements TabInterface
{
    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory
            ->create(['data' => ['html_id_prefix' => 'page_image_']]);

        $model = $this->_coreRegistry->registry('newspost_post');

        $isElementDisabled = false;

        $layoutFieldset = $form->addFieldset(
            'image_fieldset',
            [
                'legend' => __('Image'),
                'class' => 'fieldset-wide',
                'disabled' => $isElementDisabled
            ]
        );
        $layoutFieldset->addType(
            'image',
            '\MageArray\News\Block\Adminhtml\Newspost\Helper\Image'
        );

        $layoutFieldset->addField(
            'image_thumb',
            'image',
            [
                'name' => 'image_thumb',
                'label' => __('Thumbnail Image'),
                'title' => __('Thumbnail Image'),
                'disabled' => $isElementDisabled
            ]
        );

        $layoutFieldset->addField(
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Main Image'),
                'title' => __('Main Image'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $this->_eventManager
            ->dispatch(
                'adminhtml_news_edit_tab_image_prepare_form',
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
        return __('Images');
    }

    /**
     * @return mixed
     */
    public function getTabTitle()
    {
        return __('Images');
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
