<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codilar\Customer\Controller\Account;

use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Codilar\Customer\Helper\Data as CustomerHelper;
/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class LoginPost extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * @var AccountRedirect
     */
    protected $accountRedirect;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private $cookieMetadataManager;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    private $customerCollecctionFactory;
    /**
     * @var CustomerHelper
     */
    private $customerHelper;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param CustomerUrl $customerHelperData
     * @param Validator $formKeyValidator
     * @param AccountRedirect $accountRedirect
     * @param CollectionFactory $customerCollecctionFactory
     * @param CustomerHelper $customerHelper
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        CustomerUrl $customerHelperData,
        Validator $formKeyValidator,
        AccountRedirect $accountRedirect,
        CollectionFactory $customerCollecctionFactory,
        CustomerHelper $customerHelper
    ) {
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->customerUrl = $customerHelperData;
        $this->formKeyValidator = $formKeyValidator;
        $this->accountRedirect = $accountRedirect;
        $this->customerCollecctionFactory = $customerCollecctionFactory;
        parent::__construct($context);
        $this->customerHelper = $customerHelper;
    }

    /**
     * Get scope config
     *
     * @return ScopeConfigInterface
     * @deprecated 100.0.10
     */
    private function getScopeConfig()
    {
        if (!($this->scopeConfig instanceof \Magento\Framework\App\Config\ScopeConfigInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\App\Config\ScopeConfigInterface::class
            );
        } else {
            return $this->scopeConfig;
        }
    }

    /**
     * Retrieve cookie manager
     *
     * @deprecated 100.1.0
     * @return \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\PhpCookieManager::class
            );
        }
        return $this->cookieMetadataManager;
    }

    /**
     * Retrieve cookie metadata factory
     *
     * @deprecated 100.1.0
     * @return \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory::class
            );
        }
        return $this->cookieMetadataFactory;
    }

    /**
     * Login post action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(){
        if ($this->session->isLoggedIn() || !$this->formKeyValidator->validate($this->getRequest())) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            $originalUsername = $login['username'];
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $validatetype = $this->findloginType($login['username']);
                    if(isset($_POST['send'])){
                        if($validatetype=='Mobile'){
                            $login['username'] =  $this->authenticateByTelephone($login['username']);
                        }else if($validatetype=='Email'){

                        }else{
                            $this->messageManager->addErrorMessage("Invalid Username!");
                            return $this->accountRedirect->getRedirect();
                        }
                        $customer = $this->customerAccountManagement->authenticate($login['username'], $login['password']);
                        $this->session->setCustomerDataAsLoggedIn($customer);
                        $this->session->regenerateId();
                        if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                            $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                            $metadata->setPath('/');
                            $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
                        }
                        $redirectUrl = $this->accountRedirect->getRedirectCookie();
                        if (!$this->getScopeConfig()->getValue('customer/startup/redirect_dashboard') && $redirectUrl) {
                            $this->accountRedirect->clearRedirectCookie();
                            $resultRedirect = $this->resultRedirectFactory->create();
                            // URL is checked to be internal in $this->_redirect->success()
                            $resultRedirect->setUrl($this->_redirect->success($redirectUrl));
                            return $resultRedirect;
                        }
                    }
                    else{
                        if($login['username'] != $this->customerHelper->getCustomerPhoneNumberInSession()){
                            $message = __('The OTP you entered is incorrect for the given mobile number.');
                            $this->messageManager->addErrorMessage($message);
                            return $this->accountRedirect->getRedirect();
                        }
                        $login['username'] =  $this->authenticateByTelephone($login['username'],$login['password']);
                        $validate = $this->customerHelper->validateOtp($login['password']);
                        $customer = $this->customerAccountManagement->authenticateLoginWithOtp($login['username'], $login['password'],$validate);
                        $this->session->setCustomerDataAsLoggedIn($customer);
                        $this->session->regenerateId();
                        if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                            $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                            $metadata->setPath('/');
                            $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
                        }
                        $redirectUrl = $this->accountRedirect->getRedirectCookie();
                        if (!$this->getScopeConfig()->getValue('customer/startup/redirect_dashboard') && $redirectUrl) {
                            $this->accountRedirect->clearRedirectCookie();
                            $resultRedirect = $this->resultRedirectFactory->create();
                            // URL is checked to be internal in $this->_redirect->success()
                            $resultRedirect->setUrl($this->_redirect->success($redirectUrl));
                            return $resultRedirect;
                        }
                    }
                } catch (EmailNotConfirmedException $e) {
                    $value = $this->customerUrl->getEmailConfirmationUrl($login['username']);
                    $message = __(
                        'This account is not confirmed. <a href="%1">Click here</a> to resend confirmation email.',
                        $value
                    );
                    $this->messageManager->addError($message);
                    $this->session->setUsername($originalUsername);
                } catch (UserLockedException $e) {
                    $message = __(
                        'The password/otp you entered is incorrect or your account is temporarily disabled.'
                    );
                    $this->messageManager->addError($message);
                    $this->session->setUsername($originalUsername);
                } catch (AuthenticationException $e) {
                    $message = __('The password/otp you entered is incorrect. Please try again.');
                    $this->messageManager->addError($message);
                    $this->session->setUsername($originalUsername);
                } catch (LocalizedException $e) {
                    $message = $e->getMessage();
                    $this->messageManager->addError($message);
                    $this->session->setUsername($originalUsername);
                } catch (\Exception $e) {
                    // PA DSS violation: throwing or logging an exception here can disclose customer password
                    $this->messageManager->addError(
                        __('An unspecified error occurred. Please contact us for assistance.'.$e)
                    );
                }
            } else {
                $this->messageManager->addError(__('A login and a password are required.'));
            }
        }

        return $this->accountRedirect->getRedirect();
    }

    /**
     * @param $telephone
     * @return bool
     */
    public function authenticateByTelephone($telephone)
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

    /**
     * @param $username
     * @return bool|string
     */
    public function findloginType($username){
        if($this->isValidatemobile($username)){
            return "Mobile";
        }else if (preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $username)){
            return "Email"; 
        }
        else{
            return false;
        }
    }

    /**
     * @param $phoneNumber
     * @return bool
     */
    public function isValidatemobile($phoneNumber){
        if (preg_match('/^([+]?\d{1,15})$/', $phoneNumber)) {
            return true;
        } else {
            return false;
        }
    }

}