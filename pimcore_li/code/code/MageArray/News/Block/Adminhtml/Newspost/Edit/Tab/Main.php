<?php

namespace MageArray\News\Block\Adminhtml\Newspost\Edit\Tab;

use MageArray\News\Model\Categories;
use MageArray\News\Model\Status;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

/**
 * Class Main
 * @package MageArray\News\Block\Adminhtml\Newspost\Edit\Tab
 */
class Main extends Generic implements TabInterface
{
    /**
     * @var Store
     */
    protected $_systemStore;
    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * @var Status
     */
    protected $_status;

    /**
     * @var Categories
     */
    protected $_categories;

    /**
     * Main constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param Config $wysiwygConfig
     * @param Status $status
     * @param Categories $categories
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Config $wysiwygConfig,
        Status $status,
        Categories $categories,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_status = $status;
        $this->_categories = $categories;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('newspost_post');

        $isElementDisabled = false;

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('News Post Information')]
        );
        if ($model->getId()) {
            $fieldset->addField(
                'newspost_id',
                'hidden',
                ['name' => 'newspost_id']
            );
        }

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Post Title'),
                'title' => __('Post Title'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
            'category',
            'multiselect',
            [
                'name' => 'category[]',
                'label' => __('Category'),
                'title' => __('Category'),
                'required' => true,
                'disabled' => $isElementDisabled,
                'values' => $this->_categories->getAllOptions()
            ]
        );

        $fieldset->addField(
            'tags',
            'text',
            [
                'name' => 'tags',
                'label' => __('Tags'),
                'title' => __('Tags'),
                'disabled' => $isElementDisabled
            ]
        );

        $wysiwygConfig = $this->_wysiwygConfig
            ->getConfig(['tab_id' => $this->getTabId()]);

        $fieldset->addField(
            'short_content',
            'editor',
            [
                'name' => 'short_content',
                'label' => __('Short Post Content'),
                'title' => __('Short Post Content'),
                'style' => 'height:10em;',
                'required' => true,
                'disabled' => $isElementDisabled,
                'config' => $wysiwygConfig
            ]
        );

        $fieldset->addField(
            'content',
            'editor',
            [
                'name' => 'content',
                'label' => __('Post Content'),
                'title' => __('Post Content'),
                'style' => 'height:10em;',
                'required' => true,
                'disabled' => $isElementDisabled,
                'config' => $wysiwygConfig
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormat(
            \IntlDateFormatter::SHORT
        );

        $fieldset->addField(
            'publish_date',
            'date',
            [
                'name' => 'publish_date',
                'label' => __('Publish Date'),
                'date_format' => 'yyyy/MM/dd',
                'required' => true,
                'disabled' => $isElementDisabled,
                'class' => 'validate-date validate-date-range date-range-custom_theme-from'
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
                'options' => $this->_status->getOptionArray(),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'source_edition',
            'text',
            [
                'name' => 'source_edition',
                'label' => __('Source Edition'),
                'title' => __('Source Edition'),
                'disabled' => $isElementDisabled
            ]
        );

        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getTabLabel()
    {
        return __('News Post Information');
    }

    /**
     * @return mixed
     */
    public function getTabTitle()
    {
        return __('News Post Information');
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
