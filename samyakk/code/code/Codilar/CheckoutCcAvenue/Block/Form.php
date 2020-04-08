<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutCcAvenue\Block;


use Codilar\CheckoutCcAvenue\Helper\RequestData;
use Codilar\CheckoutCcAvenue\Model\Config;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;

class Form extends Template
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var RequestData
     */
    private $requestData;

    /**
     * Form constructor.
     * @param Template\Context $context
     * @param Config $config
     * @param OrderRepositoryInterface $orderRepository
     * @param RequestData $requestData
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Config $config,
        OrderRepositoryInterface $orderRepository,
        RequestData $requestData,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->orderRepository = $orderRepository;
        $this->requestData = $requestData;
    }

    public function getSubmitUrl()
    {
        return $this->config->getPaymentUrl();
    }

    public function getAccessCode()
    {
        return $this->config->getGatewayId();
    }

    public function getEncryptedData()
    {
        $id = $this->getRequest()->getParam('order_id');
        try {
            return $this->requestData->getRequestData($id);
        } catch (NoSuchEntityException $e) {
        }
    }

    public function getRedirectingMessage() {
        return $this->config->getRedirectMessage();
    }

    public function getOrder()
    {
        $id = $this->getRequest()->getParam('order_id');
        return $this->orderRepository->get($id);
    }
}