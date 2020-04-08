<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 1/4/2018
 * Time: 12:20 PM
 */

namespace Codilar\Customer\Controller\Account;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SecurityViolationException;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\UrlInterface;
use Codilar\Customer\Helper\Data as CustomerHelper;

/**
 * Class ForgotPasswordPost
 * @package Codilar\Customer\Controller\Account
 */
class ForgotPasswordPost extends Action
{
    /**
     * @var Session
     */
    private $customerSession;
    /**
     * @var AccountManagementInterface
     */
    private $customerAccountManagement;
    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    private $customerCollecctionFactory;
    /**
     * @var UrlInterface
     */
    protected $urlInterface;
    /**
     * @var CustomerHelper
     */
    private $customerHelper;

    /**
     * ForgotPasswordPost constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param Escaper $escaper
     * @param CollectionFactory $customerCollecctionFactory
     * @param UrlInterface $urlInterface
     * @param CustomerHelper $customerHelper
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        Escaper $escaper,
        CollectionFactory $customerCollecctionFactory,
        UrlInterface $urlInterface,
        CustomerHelper $customerHelper
    )
    {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->escaper = $escaper;
        $this->customerCollecctionFactory = $customerCollecctionFactory;
        $this->urlInterface = $urlInterface;
        $this->customerHelper = $customerHelper;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $email = (string)$this->getRequest()->getPost('email');
            $isMobileNumber = false;
            if (is_numeric($email)) {
                $isMobileNumber = true;
                $mobileNumber = $email;
                $email = $this->getEmailUsingMobile($mobileNumber);
                if (!$email) {
                    $this->messageManager->addError(
                        __('Your mobile number does not exist.')
                    );
                    return $resultRedirect->setPath('*/*/forgotpassword');
                }
            }
            if ($email) {
                try {
                    if ($isMobileNumber) {
                        $this->customerAccountManagement->initiatePasswordResetMobile(
                            $mobileNumber,
                            $email,
                            AccountManagement::EMAIL_RESET,
                            null
                        );
                    } else {
                        $emailExists = $this->customerCollecctionFactory->create()
                            ->addFieldToFilter("email", $email)
                            ->getFirstItem()->getEmail();
                        if (!strlen($emailExists) > 0) {
                            $this->messageManager->addError(
                                __("Email doesn't exist in our database.")
                            );
                            return $resultRedirect->setPath('*/*/forgotpassword');
                        }
                        $this->customerAccountManagement->initiatePasswordReset(
                            $email,
                            AccountManagement::EMAIL_RESET
                        );
                    }
                } catch (NoSuchEntityException $exception) {
                    // Do nothing, we don't want anyone to use this action to determine which email accounts are registered.
                    //$this->messageManager->addSuccessMessage($this->getSuccessMessage($email));
                } catch (SecurityViolationException $exception) {
                    $this->messageManager->addErrorMessage($exception->getMessage());
                    return $resultRedirect->setPath('*/*/forgotpassword');
                } catch (\Exception $exception) {
                    $this->messageManager->addExceptionMessage(
                        $exception,
                        __('We\'re unable to send the password reset email.')
                    );
                    return $resultRedirect->setPath('*/*/forgotpassword');
                }
                if ($isMobileNumber) {
                    $customerId = $this->customerHelper->getCustomer($email)->getId();
                    $resetUrl = $this->urlInterface
                        ->getUrl('customer/account/resetotp/',
                            array('mobilenumber' => $mobileNumber, 'id' => $customerId)
                        );
                    return $resultRedirect->setUrl($resetUrl);
                }
                $this->messageManager->addSuccessMessage($this->getSuccessMessage($email));
                return $resultRedirect->setPath('*/*/');
            } else {
                $this->messageManager->addErrorMessage(__('Please enter your email.'));
                return $resultRedirect->setPath('*/*/forgotpassword');
            }
        } catch(\Exception $exception) {
            $this->messageManager->addErrorMessage(__("Some error occured. Please try again later"));
            return $resultRedirect->setPath('*/*/forgotpassword');
        }
    }

    /**
     * Retrieve success message
     *
     * @param string $email
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage($email)
    {
        return __(
            'A link sent to your registered email id %1 to reset your password.',
            $this->escaper->escapeHtml($email)
        );
    }

    /**
     * @param $telephone
     * @return bool
     */
    public function getEmailUsingMobile($telephone)
    {
        $collection = $this->customerCollecctionFactory->create();
        $collection->addAttributeToSelect(['email', CustomerHelper::PHONE_NUMBER,CustomerHelper::OTP_VERIFIED,'entity_id'])
            ->addAttributeToFilter(CustomerHelper::PHONE_NUMBER,array('eq'=>"$telephone"))
            ->addAttributeToFilter(CustomerHelper::OTP_VERIFIED,array('eq'=>"1"))
            ->setPageSize(1)
            ->setCurPage(1);
        if($collection->count() >= 1 ){
            return $collection->getFirstItem()->getEmail();
        }
        return false;
    }
}