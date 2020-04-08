<?php

namespace MageArray\News\Block\Adminhtml\Newspost\Edit;

use Magento\Backend\Block\Widget\Form\Generic;

/**
 * Class Form
 * @package MageArray\News\Block\Adminhtml\Newspost\Edit
 */
class Form extends Generic
{
    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
