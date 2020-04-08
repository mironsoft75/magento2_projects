<?php
/** 
 * BSS Commerce Co. 
 * 
 * NOTICE OF LICENSE 
 * 
 * This source file is subject to the EULA 
 * that is bundled with this package in the file LICENSE.txt. 
 * It is also available through the world-wide-web at this URL: 
 * http://bsscommerce.com/Bss-Commerce-License.txt 
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE 
 * ================================================================= 
 * This package designed for Magento COMMUNITY edition 
 * BSS Commerce does not guarantee correct work of this extension 
 * on any other Magento edition except Magento COMMUNITY edition. 
 * BSS Commerce does not provide extension support in case of 
 * incorrect edition usage. 
 * ================================================================= 
 * 
 * @category   BSS 
 * @package    Bss_Gallery 
 * @author     Extension Team 
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com ) 
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt 
 */ 
namespace Bss\Gallery\Block\Adminhtml\Category\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;

class Info extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;


    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Status $newsStatus
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    )
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
        /** @var \Bss\Gallery\Model\Category $model */
        $model = $this->_coreRegistry->registry('gallery_category');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('post_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        $fieldset->addType('image', '\Bss\Gallery\Block\Adminhtml\Category\Helper\Image');
        if ($model->getCategoryId()) {
            $fieldset->addField('category_id', 'hidden', ['name' => 'category_id']);
        }

        $fieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Album Title'), 'title' => __('Album Title'), 'required' => true]
        );
        $fieldset->addField(
            'category_description',
            'text',
            ['name' => 'category_description', 'label' => __('Album Description'), 'title' => __('Album Description'), 'required' => false]
        );
        $fieldset->addField(
            'category_meta_keywords',
            'textarea',
            ['name' => 'category_meta_keywords', 'label' => __('Meta Keywords'), 'title' => __('Meta Keywords'), 'required' => false]
        );
        $fieldset->addField(
            'category_meta_description',
            'textarea',
            ['name' => 'category_meta_description', 'label' => __('Meta Description'), 'title' => __('Meta Description'), 'required' => false]
        );

        $fieldset->addField(
            'item_layout',
            'select',
            [
                'label' => __('Layout'),
                'title' => __('Layout'),
                'name' => 'item_layout',
                'required' => false,
                'options' => ['1' => __('Standard'), '2' => __('Slider')]
            ]
        );

        $fieldset->addField(
            'slider_auto_play',
            'select',
            [
                'label' => __('Slide Auto Play'),
                'title' => __('Slide Auto Play'),
                'name' => 'slider_auto_play',
                'required' => false,
                'options' => ['1' => __('Yes'), '0' => __('No')]
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

        $form->setValues($model->getData());
        // Append dependency javascript
        // $this->setChild('form_after', $this->getLayout()
        //     ->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence')
        //         ->addFieldMap('item_layout', 'item_layout')
        //         ->addFieldMap('slider_auto_play', 'slider_auto_play')
        //         ->addFieldDependence('slider_auto_play', 'item_layout', 2) // 2 = 'Slider'
        // );
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Album Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Album Info');
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
}
