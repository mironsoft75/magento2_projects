<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 3/12/18
 * Time: 5:15 PM
 */

namespace Codilar\CustomiseJewellery\Controller\Custom;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class Otp
 * @package Codilar\CustomiseJewellery\Controller\Custom
 */
class Otp extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    /**
     * @var \Codilar\Sms\Helper\Transport
     */
    protected $helper;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $_resultFactory;


    /**
     * Otp constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Codilar\Sms\Helper\Transport $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Codilar\Sms\Helper\Transport $helper
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_resultFactory = $context->getResultFactory();
        $this->customerSession = $customerSession;
        $this->helper = $helper;
        return parent::__construct($context);
    }

    /**
     * Send Sms
     */

    public function execute()
    {
        $post = (array)$this->getRequest()->getPost();
        $mobile = $post['phone_number'];
        $formNumber=$post['formNumber'];
        $digits = 6;
        $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        if($formNumber==1){
            $this->customerSession->setCustomerOtp1($otp);
            $this->customerSession->setCustomerPhoneNumber1($mobile);
        }
        elseif ($formNumber==2){
            $this->customerSession->setCustomerOtp2($otp);
            $this->customerSession->setCustomerPhoneNumber2($mobile);
        }
        elseif ($formNumber==3){
            $this->customerSession->setCustomerOtp3($otp);
            $this->customerSession->setCustomerPhoneNumber3($mobile);
        }
        $this->helper->sendSms($mobile, 'Your OTP for Mobile verification is :' . $otp);
    }
}