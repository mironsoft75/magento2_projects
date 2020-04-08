<?php
namespace Bss\Gallery\Block\Adminhtml\Item\Edit;

/**
 * Adminhtml item edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    protected $_categories;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Bss\Gallery\Model\Item\Source\Categories $categories,
        array $data = []
    )
    {
        $this->_systemStore = $systemStore;
        $this->_categories = $categories;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('item_form');
        $this->setTitle(__('Item Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Bss\Gallery\Model\Item $model */
        $model = $this->_coreRegistry->registry('gallery_item');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );

        $form->setHtmlIdPrefix('post_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );
        $fieldset->addType('image', '\Bss\Gallery\Block\Adminhtml\Item\Helper\Image');

        if ($model->getItemId()) {
            $fieldset->addField('item_id', 'hidden', ['name' => 'item_id']);
        }

        $fieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Item Title'), 'title' => __('Item Title'), 'required' => true]
        );

        $fieldset->addField(
            'image',
            'image',
            array(
                'name' => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
                'required' => true
            )
        );

        $fieldset->addField(
            'video',
            'text',
            ['name' => 'video', 'label' => __('Video'), 'title' => __('Video'), 'required' => false, 'after_element_html' => '<small>Show youtube video when click image</small>',]
        );

        $fieldset->addField(
            'sorting',
            'text',
            ['name' => 'sorting', 'label' => __('Sort Order'), 'title' => __('Sort Order'), 'required' => false]
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

        // Get all the categories that in the database
        $allCategories = $this->_categories->toOptionArray();
        $model->setData('category_ids', $this->_categories->getCategoryIds());
        $fieldset->addField(
            'category_ids',
            'multiselect',
            [
                'label' => __('Select Albums'),
                'title' => __('Select Albums'),
                'required' => false,
                'name' => 'category_ids[]',
                'values' => $allCategories
            ]
        );

        $fieldset->addField(
            'description',
            'editor',
            [
                'name' => 'description',
                'label' => __('description'),
                'title' => __('description'),
                'style' => 'height:5em',
                'required' => true
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
