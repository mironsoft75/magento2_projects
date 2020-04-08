<?php

namespace Magento\Ccavenuepay\Controller\Ccavenuepay;

use Magento\Ccavenuepay\Controller\Ccavenuepay;
use Magento\Ccavenuepay\Model\Config;
use Magento\Sales\Model\Order;
use Magento\Framework\Controller\ResultFactory;

class ReturnUrl extends \Magento\Ccavenuepay\Controller\Ccavenuepay {

    protected $logger;
    protected $allowedOrderStates = [
        Order::STATE_PROCESSING,
        Order::STATE_COMPLETE,
    ];

    /**
     * When a customer return to website from payflow gateway.
     *
     * @return void
     */
    public function execute() {
        $ccavenuepay = $this->_objectManager->get('Magento\Ccavenuepay\Model\Ccavenuepay');
        $response = $this->getRequest()->getPostValue();

        $this->logger->info(print_r($_POST, true));
        $this->logger->info("response");
        $this->logger->info(print_r($response, true));
        $status = true;
        $encrypted_data = '';
        $order = $this->_getCheckout()->getLastRealOrder();

        if (empty($response)) {
            $status = false;
        }
        $encryption_key = '';
        $rcvdString = '';
        $encResponse = '';

        $quoteId = $this->_getCheckout()->getLastRealOrder()->getQuoteId();
        $quoteId = $this->_getCcavenuepayPostSession()->getQuoteId();
        $redirectBlock = $this->_view->getLayout()->getBlock($this->_redirectBlockName);

        $encryption_key = $ccavenuepay->getConfigData('encryption_key');
        $access_code = $ccavenuepay->getConfigData('access_code');
        if (isset($response["encResp"])) {
            $encResponse = $response["encResp"];
        }
        $rcvdString = $ccavenuepay->decrypt($encResponse, $encryption_key);
        $decryptValues = explode('&', $rcvdString);

        $this->logger->info("<pre>decryptValues");
        $this->logger->info(print_r($decryptValues, true));

        $dataSize = sizeof($decryptValues);
        $Order_Id = '';
        $tracking_id = '';
        $order_status = '';
        $response_array = array();
        for ($i = 0; $i < count($decryptValues); $i++) {
            $information = explode('=', $decryptValues[$i]);
            $this->logger->info("information");
            $this->logger->info(print_r($information, true));

            if (count($information) == 2) {
                $response_array[$information[0]] = $information[1];
            }
        }
        $this->logger->info("Response Array");
        $this->logger->info($response_array);
        $this->logger->info("Array End===");
        if (isset($response_array['order_id']))
            $resOrderId = $response_array['order_id'];
        if (isset($response_array['tracking_id']))
            $tracking_id = $response_array['tracking_id'];
        if (isset($response_array['order_status']))
            $order_status = $response_array['order_status'];
        if (isset($response_array['currency']))
            $currency = $response_array['currency'];
        if (isset($response_array['amount']))
            $amount = number_format($response_array['amount'], 2);
        $order_history_comments = '';
        $order_history_keys = array('tracking_id', 'failure_message', 'payment_mode', 'card_name', 'status_code', 'status_message', 'bank_ref_no');

        foreach ($order_history_keys as $order_history_key) {
            if ((isset($response_array[$order_history_key])) && trim($response_array[$order_history_key]) != '') {
                if (trim($response_array[$order_history_key]) == 'null')
                    continue;
                $order_history_comments .= $order_history_key . " : " . $response_array[$order_history_key].'\n';
            }
        }

        $order_history_comments_array = array();
        $order_history_comments_array[] = $order_history_comments;
        $this->logger->info("order_history_comments Array");
        $this->logger->info($order_history_comments);

        if ($order_status == "Success") {
            $order = $this->_orderFactory->create()->loadByIncrementId($this->_checkoutSession->getLastRealOrderId());

            $order_total = number_format($order->getGrandTotal(), 2);
            $orderId = $order->getIncrementId();

            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            $passed_status = $ccavenuepay->getConfigData('payment_success_order_status');
            $message = __('Your payment is authorized.');

            if($order_total == $amount && $orderId == $resOrderId){
                $order->addStatusToHistory($passed_status, $message, true);
                $order->setStatus($passed_status);
                $order->setState($passed_status, true, $message);
                $order->addStatusHistoryComment($order_history_comments, $passed_status);
                $order->save();
            }else{
                $passed_status = 'fraud';
                $order->addStatusToHistory($passed_status, 'Security Error. Illegal access detected', true);
                $order->setStatus($passed_status);
                $order->setState($passed_status, true, 'Security Error. Illegal access detected');
                $order->addStatusHistoryComment($order_history_comments, $passed_status);
                $order->save();
                $this->messageManager->addError('Security Error. Illegal access detected');
                return $resultRedirect->setPath('checkout/onepage/success');
            }

            $payment_confirmation_mail = $ccavenuepay->getConfigData('payment_confirmation_mail');
            $order_confirmation_mail = $ccavenuepay->getConfigData('order_confirmation_mail_before_payment');
            if ($order_confirmation_mail == 0) {
                $order->setCanSendNewEmailFlag(true);
            }
            if ($payment_confirmation_mail == 1) {
                $ccavenuepay->getCcavenueOrderMailSender($order);
                $this->logger->info("Order Email Sent");
            }
            
            $this->logger->info("Your Order Success");
            $this->messageManager->addSuccess($message);
            return $resultRedirect->setPath('checkout/onepage/success');
        } else if ($order_status === "Aborted") {
            $message = __('CCAvenue Payment has been cancelled and the transaction has been declined.');
        } else if ($order_status === "Failure") {
            $message = __('Thank you for shopping with us. However, the transaction has been declined.');
        } else {
            $message = __('Security Error. Illegal access detected');
        }

        $this->logger->info("message");
        $this->logger->info($message);
        $gotoSection = $this->_cancelPayment($message);
        $this->_getCcavenuepayPostSession()->unsetData('quote_id');
        $this->logger->info("gotoSection");
        $this->logger->info($gotoSection);
        $this->logger->info("====message");
        $this->logger->info($message);
        $this->messageManager->addError($message);
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('checkout/cart');
        /** @var \Magento\Checkout\Block\Onepage\Success $redirectBlock */
    }

    /**
     * Check order state
     *
     * @param Order $order
     * @return bool
     */
    protected function checkOrderState(Order $order) {
        return in_array($order->getState(), $this->allowedOrderStates);
    }

    /**
     * Check requested payment method
     *
     * @param Order $order
     * @return bool
     */
    protected function checkPaymentMethod(Order $order) {
        $payment = $order->getPayment();
        return in_array($payment->getMethod(), $this->allowedPaymentMethodCodes);
    }

}

