<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    pmj-internal
 * @package    pmj-internal
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     pmj-internal
 * @author       Codilar Team
 **/
namespace Codilar\ProductImageAndVideos\Block\Adminhtml\Images;


use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\AuthorizationInterface;

/**
 * Class ImagesButton
 * @package Codilar\ProductImageAndVideos\Block\Adminhtml\Images
 */
class ImagesButton implements ButtonProviderInterface
{
    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var Context
     */
    private $context;

    /**
     * CustomButton constructor.
     *
     * @param AuthorizationInterface $authorization
     * @param Context $context
     */
    public function __construct(
        AuthorizationInterface $authorization,
        Context $context
    )
    {
        $this->authorization = $authorization;
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {


        return [
            'label' => __('Add Images To Products'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'primary',
            'sort_order' => -10
        ];
    }

    /**
     * Get URL for Add Image  button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->context->getUrlBuilder()->getUrl('import/images/addimage', []);
    }
}