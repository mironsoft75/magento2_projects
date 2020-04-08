<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 9/11/18
 * Time: 3:20 PM
 */
namespace Codilar\StoneAndMetalRates\Block\Adminhtml\Stone\Edit;
/**
 * /**
 * Class GenericButton
 * @package Codilar\StoneAndMetalRates\Block\Adminhtml\Stone\Edit
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
    )
    {
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
}

