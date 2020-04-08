<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/12/18
 * Time: 5:44 PM
 */

namespace Codilar\CustomiseJewellery\Controller\Custom;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class Verify
 * @package Codilar\CustomiseJewellery\Controller\Custom
 */
class Verify extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $_resultFactory;

    /**
     * Verify constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     */

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_resultFactory = $context->getResultFactory();
        $this->customerSession = $customerSession;
        return parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute()
    {

        $post = $this->getRequest()->getPost();
        $otp = $post['otp'];
        $phoneNumber = $post['number'];
        $formNumber=$post['formNumber'];
        if($formNumber==1){
            $otpOrig = $this->customerSession->getCustomerOtp1();
            $phoneNumberOrig = $this->customerSession->getCustomerPhoneNumber1();
        }
        elseif ($formNumber==2){
            $otpOrig = $this->customerSession->getCustomerOtp2();
            $phoneNumberOrig = $this->customerSession->getCustomerPhoneNumber2();
        }
        elseif ($formNumber==3){
            $otpOrig = $this->customerSession->getCustomerOtp3();
            $phoneNumberOrig = $this->customerSession->getCustomerPhoneNumber3();
        }

        if ($otp == $otpOrig && $phoneNumber == $phoneNumberOrig) {
            $response = $this->resultFactory
                ->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
                ->setData([
                    'status' => "true",
                    'message' => "your request has received."
                ]);

            return $response;
        } else if ($otp != $otpOrig) {
            $response = $this->resultFactory
                ->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
                ->setData([
                    'status' => "otpError",
                    'message' => "OTP is Invalid."
                ]);

            return $response;

        } else if ($phoneNumber != $phoneNumberOrig) {
            $response = $this->resultFactory
                ->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
                ->setData([
                    'status' => "phoneError",
                    'message' => "Mobile Number has been Changed."
                ]);

            return $response;

        }


    }
}