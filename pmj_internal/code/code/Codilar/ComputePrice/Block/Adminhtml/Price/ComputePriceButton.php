<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/8/19
 * Time: 10:48 AM
 */

namespace Codilar\ComputePrice\Block\Adminhtml\Price;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\AuthorizationInterface;

/**
 * Class ComputePriceButton
 *
 * @package Codilar\ComputePrice\Block\Adminhtml
 */
class ComputePriceButton implements ButtonProviderInterface
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
     * Get Button Data
     *
     * @return array
     */
    public function getButtonData()
    {


        return [
            'label' => __('Compute Products Price'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'primary',
            'sort_order' => 0
        ];
    }

    /**
     * Get URL Create Video button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->context->getUrlBuilder()->getUrl('computeprice/price/computeprice', []);
    }
}