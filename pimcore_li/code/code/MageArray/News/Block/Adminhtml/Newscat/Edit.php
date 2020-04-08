<?php

namespace MageArray\News\Block\Adminhtml\Newscat;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

class Edit extends Container
{
    protected $_coreRegistry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_objectId = 'cat_id';
        $this->_blockGroup = 'MageArray_News';
        $this->_controller = 'adminhtml_newscat';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Category'));
        $this->buttonList->update('delete', 'label', __('Delete'));

        $this->buttonList->add(
            'save_and_continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ],
                    ],
                ],
            ],
            10
        );
    }

    /**
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            '*/*/save',
            ['_current' => true, 'back' => 'edit', 'tab' => '{{tab_id}}']
        );
    }
}
