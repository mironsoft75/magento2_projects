<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Tags\Edit;

class Tagged extends \Magento\Backend\Block\Widget\Form\Generic
{
    const MODE_ANY = 0;
    const MODE_SELECTED = 1;
    const MODE_MY = 2;

    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');
        
        $fieldset = $form->addFieldset('amblog_products_fieldset', ['legend' => __('Edit Tag')]);

        $grid = $this->getChildBlock('grid');

        $fieldset->addField('products_list', 'hidden', [
            'after_element_html' => "<div>{$grid->toHtml()}</div>",
        ]);

        $this->setForm($form);
        
        return parent::_prepareForm();
    }
}
