<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Block\Adminhtml\Banner\Edit\Tab;

use Codilar\BannerSlider\Helper\Data;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magestore\Bannerslider\Model\Banner as BannerModel;
use Magestore\Bannerslider\Model\ResourceModel\Value\CollectionFactory;
use Magestore\Bannerslider\Model\SliderFactory;
use Magestore\Bannerslider\Model\Status;

class Banner extends Generic implements TabInterface
{
    /**
     * @var DataObjectFactory
     */
    protected $_objectFactory;

    /**
     * value collection factory.
     *
     * @var CollectionFactory
     */
    protected $_valueCollectionFactory;

    /**
     * slider factory.
     *
     * @var SliderFactory
     */
    protected $_sliderFactory;

    /**
     * @var BannerModel
     */
    protected $_banner;

    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    protected $dateTime;

    /**
     * constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param DataObjectFactory $objectFactory
     * @param BannerModel $banner
     * @param CollectionFactory $valueCollectionFactory
     * @param SliderFactory $sliderFactory
     * @param Config $wysiwygConfig
     * @param Timezone $dateTime
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        DataObjectFactory $objectFactory,
        BannerModel $banner,
        CollectionFactory $valueCollectionFactory,
        SliderFactory $sliderFactory,
        Config $wysiwygConfig,
        Timezone $dateTime,
        array $data = []
    ) {
        $this->_objectFactory = $objectFactory;
        $this->_banner = $banner;
        $this->_valueCollectionFactory = $valueCollectionFactory;
        $this->_sliderFactory = $sliderFactory;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->dateTime = $dateTime;
        parent::__construct($context, $registry, $formFactory, $data);
    }


    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('page.title')->setPageTitle($this->getPageTitle());

        Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'Magestore\Bannerslider\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout().'_fieldset_element'
            )
        );

        return $this;

    }


    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $bannerAttributes = $this->_banner->getStoreAttributes();
        $bannerAttributesInStores = ['store_id' => ''];

        foreach ($bannerAttributes as $bannerAttribute) {
            $bannerAttributesInStores[$bannerAttribute.'_in_store'] = '';
        }

        $dataObj = $this->_objectFactory->create(
            ['data' => $bannerAttributesInStores]
        );
        $model = $this->_coreRegistry->registry('banner');

        if ($sliderId = $this->getRequest()->getParam('current_slider_id')) {
            $model->setSliderId($sliderId);
        }

        $dataObj->addData($model->getData());

        $storeViewId = $this->getRequest()->getParam('store');

        $attributesInStore = $this->_valueCollectionFactory
            ->create()
            ->addFieldToFilter('banner_id', $model->getId())
            ->addFieldToFilter('store_id', $storeViewId)
            ->getColumnValues('attribute_code');

        /** @var Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix($this->_banner->getFormFieldHtmlIdPrefix());

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Banner Information')]);

        if ($model->getId()) {
            $fieldset->addField('banner_id', 'hidden', ['name' => 'banner_id']);
        }

        $elements = [];
        $elements['name'] = $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
            ]
        );

        $elements['status'] = $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Banner Status'),
                'name' => 'status',
                'options' => Status::getAvailableStatuses(),
            ]
        );

        $elements['order_banner'] = $fieldset->addField(
            'order_banner',
            'text',
            [
                'title' => __('Sort Order'),
                'label' => __('Sort Order'),
                'name' => 'order_banner',
            ]
        );

        $elements['banner_device'] = $fieldset->addField(
            'banner_device',
            'select',
            [
                'label' => __('Device'),
                'title' => __('Device'),
                'name' => 'banner_device',
                'options' => Data::DEVICES,
            ]
        );

        $elements['type'] = $fieldset->addField(
            'type',
            'select',
            [
                'label' => __('Type'),
                'title' => __('Type'),
                'name' => 'type',
                'options' => Data::TYPES,
            ]
        );

        $fieldset->addField(
            'category-attributes',
            'note',
            [
                'label' => __('Category'),
                'required' => false,
                'text' => '<div id="category-attributez">' . $this->getLayout()->createBlock(
                        'Codilar\BannerSlider\Block\Adminhtml\Banner\Edit\Category\FilterAttributes'
                    )->toHtml()
                    . '</div>'
            ]
        );

        $elements['product_id'] = $fieldset->addField(
            'product_id',
            'text',
            [
                'title' => __('Product Id'),
                'label' => __('Product Id'),
                'name' => 'product_id',
            ]
        );
        $elements['caption'] = $fieldset->addField(
            'caption',
            'textarea',
            [
                'title' => __('Caption'),
                'label' => __('Caption'),
                'name' => 'caption',
            ]
        );
        $slider = $this->_sliderFactory->create()->load($sliderId);

        if ($slider->getId()) {
            $elements['slider_id'] = $fieldset->addField(
                'slider_id',
                'select',
                [
                    'label' => __('Slider'),
                    'name' => 'slider_id',
                    'values' => [
                        [
                            'value' => $slider->getId(),
                            'label' => $slider->getTitle(),
                        ],
                    ],
                ]
            );
        } else {
            $elements['slider_id'] = $fieldset->addField(
                'slider_id',
                'select',
                [
                    'label' => __('Slider'),
                    'name' => 'slider_id',
                    'values' => $model->getAvailableSlides(),
                ]
            );
        }

        $elements['image_alt'] = $fieldset->addField(
            'image_alt',
            'text',
            [
                'title' => __('Alt Text'),
                'label' => __('Alt Text'),
                'name' => 'image_alt',
                'note' => 'Used for SEO',
            ]
        );

        $wysiwygConfig = $this->_wysiwygConfig->getConfig();

        $elements['click_url'] = $fieldset->addField(
            'click_url',
            'text',
            [
                'title' => __('URL'),
                'label' => __('URL'),
                'name' => 'click_url',
            ]
        );
        /** Desktop image */
        $elements['image'] = $fieldset->addField(
            'image',
            'image',
            [
                'title' => __('Desktop Image'),
                'label' => __('Desktop Image'),
                'name' => 'image',
                'note' => 'Allow image type: jpg, jpeg, gif, png',
            ]
        );
        $elements['image_text'] = $fieldset->addField(
            'image_text',
            'text',
            [
                'title' => __('Desktop Image text'),
                'label' => __('Desktop Image text'),
                'name' => 'image_text',
            ]
        );

        /** Mobile image */
        $elements['mobile_image'] = $fieldset->addField(
            'mobile_image',
            'image',
            [
                'title' => __('Mobile Image'),
                'label' => __('Mobile Image'),
                'name' => 'mobile_image',
                'note' => 'Allow image type: jpg, jpeg, gif, png',
            ]
        );
        $elements['mobile_image_text'] = $fieldset->addField(
            'mobile_image_text',
            'text',
            [
                'title' => __('Mobile Image text'),
                'label' => __('Mobile Image text'),
                'name' => 'mobile_image_text',
            ]
        );

        /** Tablet image */
        $elements['tablet_image'] = $fieldset->addField(
            'tablet_image',
            'image',
            [
                'title' => __('Tablet Image'),
                'label' => __('Tablet Image'),
                'name' => 'tablet_image',
                'note' => 'Allow image type: jpg, jpeg, gif, png',
            ]
        );
        $elements['tablet_image_text'] = $fieldset->addField(
            'tablet_image_text',
            'text',
            [
                'title' => __('Tablet Image text'),
                'label' => __('Tablet Image text'),
                'name' => 'tablet_image_text',
            ]
        );



        $dateFormat = 'M/d/yyyy';
        $timeFormat = 'h:mm a';
        if($dataObj->hasData('start_time')) {

            $datetime = $dataObj->getData('start_time');//$this->dateTime->date($dataObj->getData('start_time'), null, $this->_localeDate->getConfigTimezone());

            $dataObj->setData('start_time',$datetime);

        }

        if($dataObj->hasData('end_time')) {
            $datetime = $dataObj->getData('end_time');//$this->dateTime->date($dataObj->getData('end_time'), null, $this->_localeDate->getConfigTimezone());
            $dataObj->setData('end_time', $datetime);
        }

        $style = 'color: #000;background-color: #fff; font-weight: bold; font-size: 13px;';
        $elements['start_time'] = $fieldset->addField(
            'start_time',
            'date',
            [
                'name' => 'start_time',
                'label' => __('Starting time'),
                'title' => __('Starting time'),
                'required' => true,
                'readonly' => true,
                'style' => $style,
                'class' => 'required-entry',
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note' => implode(' ', [$dateFormat, $timeFormat])
            ]
        );

        $elements['end_time'] = $fieldset->addField(
            'end_time',
            'date',
            [
                'name' => 'end_time',
                'label' => __('Ending time'),
                'title' => __('Ending time'),
                'required' => true,
                'readonly' => true,
                'style' => $style,
                'class' => 'required-entry',
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note' => implode(' ', [$dateFormat, $timeFormat])
            ]
        );

        $elements['target'] = $fieldset->addField(
            'target',
            'select',
            [
                'label' => __('Target'),
                'name' => 'target',
                'values' => [
                    [
                        'value' => BannerModel::BANNER_TARGET_SELF,
                        'label' => __('New Window with Browser Navigation'),
                    ],
                    [
                        'value' => BannerModel::BANNER_TARGET_PARENT,
                        'label' => __('Parent Window with Browser Navigation'),
                    ],
                    [
                        'value' => BannerModel::BANNER_TARGET_BLANK,
                        'label' => __('New Window without Browser Navigation'),
                    ],
                ],
            ]
        );

        foreach ($attributesInStore as $attribute) {
            if (isset($elements[$attribute])) {
                $elements[$attribute]->setStoreViewId($storeViewId);
            }
        }
        $form->addValues($dataObj->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getBanner()
    {
        return $this->_coreRegistry->registry('banner');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getPageTitle()
    {
        return $this->getBanner()->getId()
            ? __("Edit Banner '%1'", $this->escapeHtml($this->getBanner()->getName())) : __('New Banner');
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Banner Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Banner Information');
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
