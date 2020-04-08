<?php

namespace Codilar\MasterTables\Block\Adminhtml\Metal\Edit;

/**
 * Class GenericButton
 * @package Codilar\MasterTables\Block\Adminhtml\Metal\Edit
 */
class GenericButton
{
    /**
     * GenericButton constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    ) {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }

    /**
     * @param string $route
     * @param array  $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['metal_id' => $this->getId()]);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->context->getRequest()->getParam('metal_id');
    }
}
