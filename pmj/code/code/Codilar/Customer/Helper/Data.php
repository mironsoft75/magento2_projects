<?php

namespace Codilar\Customer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\View\Element\BlockFactory;
use Mageplaza\SocialLogin\Model\Social;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\Data\CustomerSecureFactory;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Math\Random;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class Data extends AbstractHelper
{
    CONST STATE_CUSTOMER_VERIFIED = "1";
    CONST STATE_CUSTOMER_NOT_VERIFIED = "0";
    CONST VERIFIED_LABEL = "Verified";
    CONST NOT_VERIFIED_LABEL = "Not Verified";
    CONST PHONE_NUMBER = "phone_number";
    CONST OTP_VERIFIED = "otp_verified";
    CONST STATUS_VERIFIED = "1";
    CONST STATUS_NOT_VERIFIED = "0";
    /**
     * @var $_scopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var BlockFactory
     */
    private $blockFactory;
    /**
     * @var Social
     */
    private $social;
    /**
     * @var \Mageplaza\SocialLogin\Model\ResourceModel\Social
     */
    private $socialResource;
    /**
     * @var FormKey
     */
    protected $_formKey;
    /**
     * @var Encryptor
     */
    protected $encryptor;
    /**
     * @var \Codilar\Customer\Model\PasswordLogger
     */
    protected $_passwordLogger;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;
    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;
    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $_customerUrl;
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;
    /**
     * @var Link
     */
    protected $link;
    /**
     * @var Validator
     */
    private $formKeyValidator;
    /**
     * @var Random
     */
    private $mathRandom;
    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;
    /**
     * @var CustomerSecureFactory
     */
    private $customerSecureFactory;
    /**
     * @var ManagerInterface
     */
    private $eventManager;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var CustomerSession
     */
    private $_customerSession;
    /**
     * @var CollectionFactory
     */
    private $_collectionFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param BlockFactory $blockFactory
     * @param Social $social
     * @param \Mageplaza\SocialLogin\Model\ResourceModel\Social $socialResource
     * @param FormKey $formKey
     * @param Encryptor $encryptor
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerRegistry $customerRegistry
     * @param CustomerSecureFactory $customerSecureFactory
     * @param CustomerFactory $customerFactory
     * @param ManagerInterface $eventManager
     * @param StoreManagerInterface $storeManager
     * @param Random $mathRandom
     * @param CustomerSession $customerSession
     * @param CollectionFactory $collectionFactory
     * @param Transport $transport
     * @param Validator|null $formKeyValidator
     */
    public function __construct(
        Context $context,
        BlockFactory $blockFactory,
        Social $social,
        \Mageplaza\SocialLogin\Model\ResourceModel\Social $socialResource,
        FormKey $formKey,
        Encryptor $encryptor,
        CustomerRepositoryInterface $customerRepository,
        CustomerRegistry $customerRegistry,
        CustomerSecureFactory $customerSecureFactory,
        CustomerFactory $customerFactory,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        Random $mathRandom,
        CustomerSession $customerSession,
        CollectionFactory $collectionFactory,
        Validator $formKeyValidator = null
    )
    {
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
        $this->blockFactory = $blockFactory;
        $this->social = $social;
        $this->socialResource = $socialResource;
        $this->formKeyValidator = $formKeyValidator;
        $this->_formKey = $formKey;
        $this->encryptor = $encryptor;
        $this->customerRepository = $customerRepository;
        $this->customerRegistry = $customerRegistry;
        $this->customerSecureFactory = $customerSecureFactory;
        $this->_customerFactory = $customerFactory;
        $this->eventManager = $eventManager;
        $this->storeManager = $storeManager;
        $this->mathRandom = $mathRandom;
        $this->_customerSession = $customerSession;
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * @return bool
     */
    public function isCustomerLoggedIn(){
        return $this->_customerSession->isLoggedIn();
    }

    /**
     * @return bool
     */
    public function isCustomerSocialLogin() {
        $passwordHash = $this->_customerSession->getCustomer()->getPasswordHash();
        if($passwordHash){
            return false;
        }
        return true;
    }
    /**
     * @param string $plainText
     * @param string $cipherText
     * @param array $salts
     * @return bool
     */
    public function cipherMatch($plainText, $cipherText, $salts = [""]){
        foreach($salts as $salt) {
            if(\hash("sha256", $salt.$plainText.$salt) === $cipherText){
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getSocialLoginBlock(){
        if($this->scopeConfig->getValue('sociallogin/general/is_enabled')){
            $block = $this->blockFactory->createBlock('Mageplaza\SocialLogin\Block\Popup\Social', [
                'name'      =>  "social-login-popup-authentication-social-create"
            ]);
            $block->setTemplate("Mageplaza_SocialLogin::form/social.phtml");
            return $block->toHtml();
        }
        return "";
    }
    /**
     * @return mixed
     */
    public function getAccountSharingOptions()
    {
        return $this->_scopeConfig->getValue('customer/account_share/scope', \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function formKeyValidator(\Magento\Framework\App\RequestInterface $request)
    {
        $formKey = $request->getParam('form_key', null);
        if (!$formKey || $formKey !== $this->_formKey->getFormKey()) {
            return false;
        }
        return true;
    }

    /**
     * @param $email
     * @param $websiteId
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerByEmail($email, $websiteId)
    {
        $customer = $this->customerRepository->get($email, $websiteId);
        return $customer;
    }

    /**
     * @param $customerId
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerById($customerId)
    {
        $customer = $this->customerRepository->getById($customerId);
        return $customer;
    }
    /**
     * @param $customerId
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomer($email)
    {
        $customer = $this->customerRepository->get($email);
        return $customer;
    }

    /**
     * @return string
     */
    public function getUniqueHash()
    {
        $hash = $this->mathRandom->getUniqueHash();
        return $hash;
    }

    /**
     * @param $customerId
     * @return \Magento\Customer\Model\Data\CustomerSecure
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function retrieveSecureData($customerId)
    {
        return $this->customerRegistry->retrieveSecureData($customerId);
    }

    /**
     * @param $customer
     * @return bool
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function saveCustomer($customer)
    {
        $this->customerRepository->save($customer);
        return true;
    }

    /**
     * @return mixed
     */
    public function getCustomerFactoryObject(){
        return $this->_customerFactory->create();
    }

    /**
     * @param $eventName
     * @param $data
     */
    public function dispatchEvent($eventName,$data){
        $this->eventManager->dispatch($eventName, $data);
    }

    /**
     * @return \Magento\Store\Api\Data\WebsiteInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getWebsite(){
        return $this->storeManager->getWebsite();
    }

    /**
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
    public function getLoggedInCustomer(){
        try{
            if($this->isCustomerLoggedIn()){
                $customerId = $this->_customerSession->getId();
                return $this->customerRepository->getById($customerId);
            }
            else{
                return null;
            }
        }
        catch (\Exception $e){
            return null;
        }
    }
    /**
     * @param $customerId
     * @return \Magento\Framework\Api\AttributeInterface|null
     */
    public function getCustomerPhoneNumber($customerId){
        try{
            $customer = $this->customerRepository->getById($customerId);
            $phoneNumberAttribute = $customer->getCustomAttribute(self::PHONE_NUMBER);
            if($phoneNumberAttribute){
                $phoneNumber = $customer->getCustomAttribute(self::PHONE_NUMBER)->getValue();
                return $phoneNumber;
            }
            return null;
        }
        catch (\Exception $e){
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function getCustomerPhoneNumberFromSession(){
        $customer = $this->_customerSession->getCustomer();
        $phoneNumber = $customer->getCustomAttribute(self::PHONE_NUMBER);
        return $phoneNumber;
    }

    /**
     * @param $customerId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isCustomerOtpVerified($customerId){
        try{
            $customer = $this->customerRepository->getById($customerId);
            $otpAttribute = $customer->getCustomAttribute(self::OTP_VERIFIED);
            if($otpAttribute){
                $otpVerified = $customer->getCustomAttribute(self::OTP_VERIFIED)->getValue();
                return ($otpVerified == 1) ? true : false;
            }
            return false;
        }
        catch (\Exception $e){
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isCustomerOtpVerifiedFromSession(){
        try{
            $customerId = $this->_customerSession->getCustomer()->getId();
            $otpVerified = $this->isCustomerOtpVerified($customerId);
            return $otpVerified;
        }
        catch (\Exception $e){
            $this->_logger->debug($e->getMessage());
        }
    }

    /**
     * @param $phoneNumber
     * @return bool
     */
    public function setCustomerPhoneNumberToSession($phoneNumber){
        $this->_customerSession->setPhoneNumber($phoneNumber);
        return true;
    }

    /**
     * @return bool
     */
    public function unsetCustomerPhoneNumberInSession(){
        $this->_customerSession->unsPhoneNumber();
        return true;
    }

    /**
     * @return mixed
     */
    public function getCustomerPhoneNumberInSession(){
        return $this->_customerSession->getPhoneNumber();
    }
    /**
     * @param $otp
     * @return bool
     */
    public function setCustomerOtpToSession($otp){
        $this->_customerSession->setOtp($otp);
        return true;
    }

    /**
     * @return bool
     */
    public function getCustomerOtpInSession(){
        return $this->_customerSession->getOtp();
    }

    /**
     * @return bool
     */
    public function unsetCustomerOtpInSession(){
        $this->_customerSession->unsOtp();
        return true;
    }

    /**
     * @param $resetToken
     * @return bool
     */
    public function setResetTokenInSession($resetToken){
        $this->_customerSession->setResetToken($resetToken);
        return true;
    }

    /**
     * @return mixed
     */
    public function getResetTokenInSession(){
        return $this->_customerSession->getResetToken();
    }

    /**
     * @param $phoneNumber
     * @return bool
     */
    public function isMobileNumberAvailable($phoneNumber)
    {
        $factory = $this->_collectionFactory->create()
            ->addFieldToFilter(self::PHONE_NUMBER,$phoneNumber)
            ->addFieldToFilter(self::OTP_VERIFIED,1)
            ->getFirstItem();
        if(count($factory->getData())){
            return false;
        }
        else{
            return true;
        }
    }

    /**
     * @param $otp
     * @return bool
     */
    public function validateOtp($otp){
        $sessionOtp = $this->getCustomerOtpInSession();
        if($otp == $sessionOtp){
            return true;
        }
        return false;
    }

    /**
     * @return int|null
     */
    public function getCustomerIdFromSession(){
        if($this->_customerSession->isLoggedIn()){
            return $this->_customerSession->getId();
        }
        return null;
    }

    /**
     * @param $customerId
     * @return bool
     */
    public function setOtpVerifiedForCustomer($customerId){
        try{
            $customer = $this->getCustomerById($customerId);
            $customer->setCustomAttribute(self::OTP_VERIFIED,1);
            $this->saveCustomer($customer);
            $this->unsetCustomerPhoneNumberInSession();
            $this->unsetCustomerOtpInSession();
        }
        catch (\Exception $e){
            $this->_logger->info($e->getMessage());
        }
        return true;
    }

    /**
     * @param $customerId
     * @return bool
     */
    public function setOtpNotVerifiedForCustomer($customerId){
        try{
            $customer = $this->getCustomerById($customerId);
            $customer->setCustomAttribute(self::OTP_VERIFIED,0);
            $this->saveCustomer($customer);
            $this->unsetCustomerPhoneNumberInSession();
            $this->unsetCustomerOtpInSession();
        }
        catch (\Exception $e){
            $this->_logger->info($e->getMessage());
        }
        return true;
    }
}