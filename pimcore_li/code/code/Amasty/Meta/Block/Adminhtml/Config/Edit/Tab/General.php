<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Meta
 */

namespace Amasty\Meta\Block\Adminhtml\Config\Edit\Tab;
use Magento\Framework\Data\FormFactory;

class General extends \Magento\Backend\Block\Widget\Form
{
    /**
     * @var \Amasty\Meta\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Amasty\Meta\Model\System\Store
     */
    protected $store;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $_formFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\Meta\Helper\Data $dataHelper,
        \Magento\Framework\Registry $registry,
        FormFactory $formFactory,
        \Amasty\Meta\Model\System\Store $store,
        array $data = []
    ) {
        $this->dataHelper = $dataHelper;
        $this->store = $store;
        $this->_coreRegistry = $registry;
        $this->_formFactory = $formFactory;
        parent::__construct($context, $data);
    }

    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $this->setForm($form);

        $fldCond = $form->addFieldset(
            'attr',
            array('legend' => __('Main Category'))
        );

        $fldCond->addField('category_id',
            'select',
            array(
                'label'  => __('Category is'),
                'name'   => 'category_id',
                'values' => $this->dataHelper->getTree(),
            )
        );

        if (! $this->_storeManager->isSingleStoreMode()) {
            $fldCond->addField('store_id',
                'select',
                array(
                    'label'  => __('Apply For'),
                    'name'   => 'store_id',
                    'values' => $this->store->getStoreValuesForForm(true),
                )
            );
        }

        //set form values
        $model = $this->_coreRegistry->registry('ammeta_config');
        $form->setValues($model->getData());

        return parent::_prepareForm();
    }
}