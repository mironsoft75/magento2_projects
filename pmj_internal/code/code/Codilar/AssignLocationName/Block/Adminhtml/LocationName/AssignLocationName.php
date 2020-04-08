<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28/11/19
 * Time: 3:02 PM
 */

namespace Codilar\AssignLocationName\Block\Adminhtml\LocationName;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\AuthorizationInterface;

class AssignLocationName implements ButtonProviderInterface
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
            'label' => __('AssignLocationName'),
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
            ->getUrl('locationname/locationname/assignlocationname', []);
    }
}