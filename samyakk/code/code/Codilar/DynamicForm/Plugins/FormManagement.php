<?php
/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Plugins;

use Codilar\Api\Helper\Customer;
use Codilar\Core\Helper\Product;
use Codilar\DynamicForm\Api\Data\FormInterface;
use Codilar\DynamicForm\Api\Form\ResponseRepositoryInterface;
use Codilar\DynamicForm\Api\FormManagementInterface as Subject;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Codilar\DynamicForm\Helper\Response;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class FormManagement
{
    /**
     * @var Response
     */
    private $responseHelper;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var Customer
     */
    private $customerApiHelper;
    /**
     * @var OrderItemRepositoryInterface
     */
    private $orderItemRepository;
    /**
     * @var Product
     */
    private $productHelper;
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;
    /**
     * @var ResponseRepositoryInterface
     */
    private $responseRepository;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * FormManagement constructor.
     * @param Response $responseHelper
     * @param RequestInterface $request
     * @param Customer $customerApiHelper
     * @param OrderItemRepositoryInterface $orderItemRepository
     * @param Product $productHelper
     * @param FormRepositoryInterface $formRepository
     * @param ResponseRepositoryInterface $responseRepository
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Response $responseHelper,
        RequestInterface $request,
        Customer $customerApiHelper,
        OrderItemRepositoryInterface $orderItemRepository,
        Product $productHelper,
        FormRepositoryInterface $formRepository,
        ResponseRepositoryInterface $responseRepository,
        OrderRepositoryInterface $orderRepository
    )
    {
        $this->responseHelper = $responseHelper;
        $this->request = $request;
        $this->customerApiHelper = $customerApiHelper;
        $this->orderItemRepository = $orderItemRepository;
        $this->productHelper = $productHelper;
        $this->formRepository = $formRepository;
        $this->responseRepository = $responseRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Subject $subject
     * @param $id
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeRenderById(Subject $subject, $id)
    {
        $orderId = $this->request->getParam("order_id");
        $orderItemId = $this->request->getParam("order_item_id");
        $token = $this->customerApiHelper->getToken();
        if ($orderId && $orderItemId && $token ) {
            $customer = $this->customerApiHelper->getCustomerIdByToken(true);

            if (!$token) {
                throw new NoSuchEntityException(__("The Form does not exists"));
            }
            if (!$orderItemId) {
                return $id;
            }
            $orderItem = $this->orderItemRepository->get($orderItemId);
            $orderId = $orderItem->getOrderId();
            $order = $this->orderRepository->get($orderId);
            if ($order->getCustomerId() != $customer->getId()) {
                throw new NoSuchEntityException(__("Illegal Access!"));
            }
            $product = $this->productHelper->getProduct($orderItem->getProductId());
            $customForms = $product->getData("custom_forms");
            if (!$customForms) {
                throw new NoSuchEntityException(__("The Form does not exists"));
            }
            $customForms = explode(",", $customForms);
            if (in_array($id, $customForms)) {
                $form = $this->formRepository->load($id);
                $isFormFilled = $this->checkIsFormFilled($form, $order->getIncrementId());
                if ($isFormFilled) {
                    throw new NoSuchEntityException(__("The Form does not exists"));
                }
            }
        }

        return $id;
    }

    /**
     * @param FormInterface $form
     * @param string $incrementId
     * @return boolean
     */
    protected function checkIsFormFilled($form, $incrementId)
    {
        try {
            $this->responseHelper->getFilledResponse($form->getId(), $incrementId);
            return true;
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}