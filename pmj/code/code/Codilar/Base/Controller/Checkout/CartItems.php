<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/12/18
 * Time: 10:37 AM
 */

namespace Codilar\Base\Controller\Checkout;

use Magento\Checkout\Model\Cart;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
/**
 * Class CartItems
 * @package Codilar\Base\Controller\Checkout
 */
class CartItems extends \Magento\Framework\App\Action\Action
{

    /**
     * @var Cart
     */
    private $checkoutCart;
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * CartItems constructor.
     * @param Context $context
     * @param Cart $checkoutCart
     * @param CheckoutSession $checkoutSession
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Context $context,
        Cart $checkoutCart,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession
    ) {
        parent::__construct($context);
        $this->checkoutCart = $checkoutCart;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if($this->customerSession->isLoggedIn()){
            $items = $this->checkoutCart->getQuote()->getAllItems();
            $response = [
                'count'    =>  count($items),
                'status'   =>  0
            ];
        }
        else{
            $items = $this->checkoutCart->getQuote()->getAllItems();
            $response = [
                'count'    =>  count($items),
                'status'   =>  1
            ];
        }
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData($response);
        return $result;
    }
}