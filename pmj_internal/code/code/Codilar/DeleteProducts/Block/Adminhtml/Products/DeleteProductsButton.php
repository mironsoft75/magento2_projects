<?php
/**
 * @package   CategoryImporter
 * @author    Splash
 */

namespace Codilar\DeleteProducts\Block\Adminhtml\Products;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\AuthorizationInterface;

/**
 * Class DeleteProductsButton
 * @package Codilar\DeleteProducts\Block\Adminhtml\Products
 */
class DeleteProductsButton implements ButtonProviderInterface
{
    /**
     * Authorization
     *
     * @var AuthorizationInterface
     */
    protected $authorization;

    /**
     * Context
     *
     * @var Context
     */
    protected $context;

    /**
     * DeleteProductsButton constructor.
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
     * Get Button Data
     *
     * @return array
     */
    public function getButtonData()
    {


        return [
            'label' => __('Delete Products'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'primary',
            'sort_order' => 0
        ];
    }

    /**
     * Get BackUrl
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->context->getUrlBuilder()
            ->getUrl('deleteproducts/products/deleteproducts', []);
    }
}