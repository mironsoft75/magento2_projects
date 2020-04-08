<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/10/19
 * Time: 12:41 PM
 */

namespace Codilar\AssignImagesAndCategories\Block\Adminhtml\Products;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\AuthorizationInterface;

/**
 * Class AssignImagesAndCategories
 *
 * @package Codilar\AssignImagesAndCategories\Block\Adminhtml\Products
 */
class AssignImagesAndCategories implements ButtonProviderInterface
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
     * @param AuthorizationInterface $authorization Authorization
     * @param Context                $context       Context
     */
    public function __construct(
        AuthorizationInterface $authorization,
        Context $context
    ) {
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
            'label' => __('AssignImagesAndCategories'),
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
            ->getUrl('imagesandcategories/products/assignimagesandcategories', []);
    }
}