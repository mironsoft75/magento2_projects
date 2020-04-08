<?php

namespace Codilar\Customer\Block;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\App\Response\Http as Response;
use Codilar\Customer\Helper\Data as CustomerHelper;
/**
 * Class Verify
 * @package Codilar\Customer\Block
 */
class Verify extends Template
{

    protected $_response;
    /**
     * @var CustomerHelper
     */
    private $_customerHelper;

    /**
     * Verify constructor.
     * @param Template\Context $context
     * @param Response $response
     * @param CustomerHelper $customerHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Response $response,
        CustomerHelper $customerHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_response = $response;
        $this->_customerHelper = $customerHelper;
    }

    /**
     * @return string
     */
    public function getVerifyUrl(){
        return $this->_urlBuilder->getUrl('customer/otp/verify');
    }

    /**
     * @return string
     */
    public function getResendUrl(){
        return $this->_urlBuilder->getUrl('customer/otp/resend');
    }

    /**
     * @return string
     */
    public function getCloseUrl(){
        return $this->_urlBuilder->getUrl('customer/account/logout');
    }

    /**
     * @return string
     */
    public function getDashboardUrl(){
        return $this->_urlBuilder->getUrl('customer/account');
    }

    /**
     * @return string
     */
    public function getNumberChangeUrl(){
        return $this->_urlBuilder->getUrl('customer/account/edit');
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber(){
        return $this->_customerHelper->getCustomerPhoneNumberInSession();
    }
}