<?php

namespace Codilar\Rapnet\Block\Adminhtml\UrlRewrite\Edit;

/**
 * Class GenericButton
 * @package Codilar\Rapnet\Block\Adminhtml\UrlRewrite\Edit
 */
class GenericButton
{
    /**
     * GenericButton constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     */
    //putting all the button methods in here.  No "right", but the whole
    //button/GenericButton thing seems -- not that great -- to begin with
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
        return $this->getUrl('*/*/delete', ['url_rewrite_id' => $this->getId()]);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->context->getRequest()->getParam('url_rewrite_id');
    }
}
