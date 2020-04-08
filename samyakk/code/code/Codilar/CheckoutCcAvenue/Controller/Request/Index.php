<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutCcAvenue\Controller\Request;

use Codilar\Api\Helper\Customer;
use Codilar\Checkout\Helper\Order as OrderHelper;
use Codilar\CheckoutCcAvenue\Block\Form;
use Codilar\CheckoutCcAvenue\Helper\RequestData;
use Codilar\CheckoutCcAvenue\Model\Config;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class Index extends Action
{
    /**
     * @var Customer
     */
    private $customerHelper;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var OrderHelper
     */
    private $orderHelper;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var RequestData
     */
    private $requestData;
    /**
     * @var RawFactory
     */
    private $rawFactory;
    /**
     * @var Form
     */
    private $block;

    /**
     * Index constructor.
     * @param Context $context
     * @param Customer $customerHelper
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderHelper $orderHelper
     * @param Config $config
     * @param RequestData $requestData
     * @param RawFactory $rawFactory
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
        Form $block
    ) {
        parent::__construct($context);
        $this->customerHelper = $customerHelper;
        $this->orderRepository = $orderRepository;
        $this->orderHelper = $orderHelper;
        $this->config = $config;
        $this->requestData = $requestData;
        $this->rawFactory = $rawFactory;
        $this->block = $block;
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
        $customerId = $this->customerHelper->getCustomerIdByToken(false, $customerToken);
        $order = $this->orderRepository->get($orderId);
        $this->orderHelper->setStatusAndState($order, $this->config->getPendingOrderStatus(), "CcAvenue Order Pending");
        if ($customerId == $order->getCustomerId()) {
            $result = $this->rawFactory->create();
            $result->setContents($this->getFormHtml());
            return $result;
        } else {
            $resultRedirect->setRefererOrBaseUrl();
            return $resultRedirect;
        }
    }

    public function getFormHtml()
    {
        return "<form id=\"ccavenue_form\" method=\"POST\" action=\"" . $this->block->escapeUrl($this->block->getSubmitUrl()) . "\">
                    <input type=\"hidden\" id=\"encRequest\" name=\"encRequest\" value=\"" . $this->block->escapeHtml($this->block->getEncryptedData()) . "\"/>
                    <input type=\"hidden\" id=\"access_code\" name=\"access_code\" value=\"" . $this->block->escapeHtml($this->block->getAccessCode()) . "\"/>
                </form>
                <div class=\"redirect-loader\">
                    <div class=\"loader\">
                        <img src=\"" . $this->block->getViewFileUrl('images/loader-1.gif') . "\"
                             alt=\"" . __('Loading...') . "\">
                    </div>
                    <div class=\"redirect-message\">" . $this->block->getRedirectingMessage() . "</div>
                </div>
                <script>
                    document.getElementById(\"ccavenue_form\").submit();
                </script>
                <style>
                    .checkoutccavenue-request-index .section-item-content.nav-sections-item-content,
                    .checkoutccavenue-request-index .copyright{
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
