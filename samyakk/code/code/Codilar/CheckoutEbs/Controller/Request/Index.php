<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/9/19
 * Time: 11:31 AM
 */

namespace Codilar\CheckoutEbs\Controller\Request;

use Codilar\Api\Helper\Customer;
use Codilar\Checkout\Helper\Order as OrderHelper;
use Codilar\CheckoutEbs\Block\Form;
use Codilar\CheckoutEbs\Block\ShippingForm;
use Codilar\CheckoutEbs\Helper\RequestData;
use Codilar\CheckoutEbs\Model\Config;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class Index
 *
 * @package Codilar\CheckoutEbs\Controller\Request
 */
class Index extends Action
{
    /**
     * Customer Helper
     *
     * @var Customer
     */
    private $_customerHelper;
    /**
     * OrderRepository
     *
     * @var OrderRepositoryInterface
     */
    private $_orderRepository;
    /**
     * OrderHelper
     *
     * @var OrderHelper
     */
    private $_orderHelper;
    /**
     * Config
     *
     * @var Config
     */
    private $_config;
    /**
     * Request Data
     *
     * @var RequestData
     */
    private $_requestData;
    /**
     * RawFactory
     *
     * @var RawFactory
     */
    private $_rawFactory;
    /**
     * Form
     *
     * @var Form
     */
    private $_block;
    /**
     * ShippingForm
     *
     * @var ShippingForm
     */
    private $_shippingForm;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param Customer $customerHelper
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderHelper $orderHelper
     * @param Config $config
     * @param RequestData $requestData
     * @param RawFactory $rawFactory
     * @param ShippingForm $shippingForm
     * @param Form $block
     */
    public function __construct(
        Context $context,
        Customer $customerHelper,
        OrderRepositoryInterface $orderRepository,
        OrderHelper $orderHelper,
        Config $config,
        RequestData $requestData,
        RawFactory $rawFactory,
        ShippingForm $shippingForm,
        Form $block
    ) {
        parent::__construct($context);
        $this->_customerHelper = $customerHelper;
        $this->_orderRepository = $orderRepository;
        $this->_orderHelper = $orderHelper;
        $this->_config = $config;
        $this->_requestData = $requestData;
        $this->_rawFactory = $rawFactory;
        $this->_shippingForm = $shippingForm;
        $this->_block = $block;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $customerToken = $this->getRequest()->getParam('token');
        $resultRedirect = $this->resultRedirectFactory->create();
        $customerId = $this->_customerHelper
            ->getCustomerIdByToken(false, $customerToken);
        $order = $this->_orderRepository->get($orderId);
        $this->_orderHelper->setStatusAndState(
            $order,
            $this->_config->getPendingOrderStatus(),
            "EBS Order Pending"
        );
        if ($customerId == $order->getCustomerId()) {
            $result = $this->_rawFactory->create();
            $result->setContents($this->getFormHtml());
            return $result;
        } else {
            $resultRedirect->setRefererOrBaseUrl();
            return $resultRedirect;
        }
    }

    /**
     * HTML Form
     *
     * @return string
     */
    public function getFormHtml()
    {
        return "<form id=\"ebs_form\" method=\"POST\" action=\"" .
            $this->_block->escapeUrl($this->_config->getPaymentUrl()) . "\">
                <input type=\"hidden\" id=\"account_id\"
                     name=\"account_id\" value=\"" .
            $this->_block->escapeHtml($this->_config->getAccountId()) . "\"/>
            <input type=\"hidden\" id=\"address\"
                        name=\"address\" value=\"" .
            $this->_block->escapeHtml($this->_block->getBillingStreet()) . "\"/>
            <input type=\"hidden\" id=\"amount\"
                        name=\"amount\" value=\"" .
            $this->_block->escapeHtml($this->_block->getAmount()) . "\"/> 
            <input type=\"hidden\" id=\"channel\"
                        name=\"channel\" value=\"" .
            $this->_block->escapeHtml($this->_block->getChannel()) . "\"/>  
            <input type=\"hidden\" id=\"city\"
                        name=\"city\" value=\"" .
            $this->_block->escapeHtml($this->_block->getBillingCity()) . "\"/>
            <input type=\"hidden\" id=\"country\"
                        name=\"country\" value=\"" .
            $this->_block->escapeHtml(
                $this->_block->getBillingCountryCode()
            ) . "\"/>
            <input type=\"hidden\" id=\"currency\"
                        name=\"currency\" value=\"" .
            $this->_shippingForm->escapeHtml(
                $this->_shippingForm->getInrCurrency()
            ) . "\"/>  
            <input type=\"hidden\" id=\"description\"
                        name=\"description\" value=\"" .
            $this->_shippingForm->escapeHtml(
                $this->_shippingForm->getDescription()
            ) . "\"/>
            <input type=\"hidden\" id=\"email\"
                        name=\"email\" value=\"" .
            $this->_block->escapeHtml($this->_block->getBillingEmail()) . "\"/>
            <input type=\"hidden\" id=\"mode\"
                        name=\"mode\" value=\"" .
            $this->_block->escapeHtml($this->_block->getMode()) . "\"/>
            <input type=\"hidden\" id=\"name\"
                        name=\"name\" value=\"" .
            $this->_block->escapeHtml(
                $this->_block->getCustomerFullName()
            ) . "\"/>
             <input type=\"hidden\" id=\"phone\"
                        name=\"phone\" value=\"" .
            $this->_block->escapeHtml(
                $this->_block->getBillingPhoneNumber()
            ) . "\"/>
            <input type=\"hidden\" id=\"postal_code\"
                        name=\"postal_code\" value=\"" .
            $this->_block->escapeHtml(
                $this->_block->getBillingPostalCode()
            ) . "\"/>
            <input type=\"hidden\" id=\"reference_no\"
                        name=\"reference_no\" value=\"" .
            $this->_block->escapeHtml($this->_block->getReferenceNumber()) . "\"/>
            <input type=\"hidden\" id=\"return_url\"
                         name=\"return_url\" value=\"" .
            $this->_block->escapeHtml($this->_block->getReturnUrl()) . "\"/>
            <input type=\"hidden\" id=\"ship_address\"
                        name=\"ship_address\" value=\"" .
            $this->_shippingForm->escapeHtml(
                $this->_shippingForm->getDeliveryAddress()
            ) . "\"/>
            <input type=\"hidden\" id=\"ship_city\"
                        name=\"ship_city\" value=\"" .
            $this->_shippingForm->escapeHtml(
                $this->_shippingForm->getDeliveryCity()
            ) . "\"/>
            <input type=\"hidden\" id=\"ship_country\"
                        name=\"ship_country\" value=\"" .
            $this->_shippingForm->escapeHtml(
                $this->_shippingForm->getDeliveryCountry()
            ) . "\"/>
            <input type=\"hidden\" id=\"ship_name\"
                        name=\"ship_name\" value=\"" .
            $this->_shippingForm->escapeHtml(
                $this->_shippingForm->getDeliveryName()
            ) . "\"/>
            <input type=\"hidden\" id=\"ship_phone\"
                        name=\"ship_phone\" value=\"" .
            $this->_shippingForm->escapeHtml(
                $this->_shippingForm->getDeliveryPhoneNumber()
            ) . "\"/>
            <input type=\"hidden\" id=\"ship_postal_code\"
                        name=\"ship_postal_code\" value=\"" .
            $this->_shippingForm->escapeHtml(
                $this->_shippingForm->getDeliveryPostCode()
            ) . "\"/>
                <input type=\"hidden\" id=\"ship_state\"
                        name=\"ship_state\" value=\"" .
            $this->_shippingForm->escapeHtml(
                $this->_shippingForm->getDeliveryState()
            ) . "\"/>
                 <input type=\"hidden\" id=\"state\"
                        name=\"state\" value=\"" .
            $this->_block->escapeHtml($this->_block->getBillingRegion()) . "\"/>
                <input type=\"hidden\" id=\"secure_hash\"
                        name=\"secure_hash\" value=\"" .
            $this->_shippingForm->escapeHtml(
                $this->_shippingForm->getSecureHash()
            ) . "\"/>
                 
                </form>                
                <div class=\"redirect-loader\">
                    <div class=\"loader\">
                        <img src=\"" . $this->_block
                ->getViewFileUrl('images/loader-1.gif') . "\"
                             alt=\"" . __('Loading...') . "\">
                    </div>
                    <div class=\"redirect-message\">" .
            $this->_block->getRedirectingMessage() . "</div>
                </div>
                <script>
                    document.getElementById(\"ebs_form\").submit();
                </script>
                <style>
                    .checkoutebs-request-index .section-item-content.nav-sections-item-content,
                    .checkoutebs-request-index .copyright{
                        display: none!important;
                    }
                    .redirect-loader{
                        position: fixed;
                        top: 30%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        margin-bottom: 20px;
                    }
                    .loader {
                        margin: 0 auto;
                        text-align: center;
                    }
                </style>";
    }
}
