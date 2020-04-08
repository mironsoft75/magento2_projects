<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codilar\Customer\Controller\Account;

use Codilar\Customer\Helper\Data as CustomerHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\UrlInterface;
/**
 * Class VerifyOtp
 * @package Codilar\Customer\Controller\Account
 */
class VerifyOtp extends \Magento\Framework\App\Action\Action
{
    /**
     * @var Validator
     */
    protected $_formKeyValidator;
    /**
     * @var CustomerHelper
     */
    protected $_customerHelper;
    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * VerifyOtp constructor.
     * @param Validator      $formKeyValidator
     * @param CustomerHelper $customerHelper
     * @param UrlInterface   $url
     * @param Context        $context
     */
    public function __construct(
        Validator $formKeyValidator,
        CustomerHelper $customerHelper,
        UrlInterface $url,
        \Magento\Framework\App\Action\Context $context
    )
    {
        $this->_formKeyValidator = $formKeyValidator;
        $this->_customerHelper = $customerHelper;
        $this->url = $url;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            $result['status'] = "0";
            $result['error'] = "Form key is not valid nor expired";
        }
        $mobileNumber = $this->getRequest()->getParam("mobile_number");
        $id = $this->getRequest()->getParam("id");
        $otp = $this->getRequest()->getParam("otp");
        $status = $this->_customerHelper->validateOtp($otp);
        if ($status) {
            $token = $this->_customerHelper->getResetTokenInSession();
            $result['status'] = "1";
            $result['token'] = $token;
            $result['url'] = $this->url->getUrl("customer/account/createpassword/");
        } else {
            $result['status'] = "0";
            $result['error'] = "Otp is invalid";
        }
        $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')
                ->jsonEncode($result)
        );
    }
}
