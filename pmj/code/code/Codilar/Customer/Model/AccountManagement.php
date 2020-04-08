<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codilar\Customer\Model;

use Codilar\Customer\Helper\Data as CustomerHelper;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\ValidationResultsInterfaceFactory;
use Magento\Customer\Helper\View as CustomerViewHelper;
use Magento\Customer\Model\Config\Share as ConfigShare;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Customer\Model\Customer\CredentialsValidator;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\EmailNotificationInterface;
use Magento\Customer\Model\Metadata\Validator;
use Magento\Eav\Model\Validator\Attribute\Backend;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObjectFactory as ObjectFactory;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
use Magento\Framework\Encryption\Helper\Security;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\ExpiredException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Exception\State\InvalidTransitionException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Math\Random;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\StringUtils as StringHelper;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface as PsrLogger;
use Codilar\Customer\Helper\Otp\Transport;

/**
 * Handle various customer account actions
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class AccountManagement extends \Magento\Customer\Model\AccountManagement
{
    const SUCCESSFUL_MESSAGE = "Otp sent successfully";
    const RESEND_SUCCESSFUL_MESSAGE = "Otp resent successfully";
    const RESEND_FAILURE_MESSAGE = "Some error occurred while sending otp. Please try again later";
    /**
     * Configuration paths for email templates and identities
     *
     * @deprecated
     */
    const XML_PATH_REGISTER_EMAIL_TEMPLATE = 'customer/create_account/email_template';

    /**
     * @deprecated
     */
    const XML_PATH_REGISTER_NO_PASSWORD_EMAIL_TEMPLATE = 'customer/create_account/email_no_password_template';

    /**
     * @deprecated
     */
    const XML_PATH_REGISTER_EMAIL_IDENTITY = 'customer/create_account/email_identity';

    /**
     * @deprecated
     */
    const XML_PATH_REMIND_EMAIL_TEMPLATE = 'customer/password/remind_email_template';

    /**
     * @deprecated
     */
    const XML_PATH_FORGOT_EMAIL_TEMPLATE = 'customer/password/forgot_email_template';

    /**
     * @deprecated
     */
    const XML_PATH_FORGOT_EMAIL_IDENTITY = 'customer/password/forgot_email_identity';

    /**
     *
     */
    const XML_PATH_IS_CONFIRM = 'customer/create_account/confirm';

    /**
     * @deprecated
     */
    const XML_PATH_CONFIRM_EMAIL_TEMPLATE = 'customer/create_account/email_confirmation_template';

    /**
     * @deprecated
     */
    const XML_PATH_CONFIRMED_EMAIL_TEMPLATE = 'customer/create_account/email_confirmed_template';

    /**
     * Constants for the type of new account email to be sent
     *
     * @deprecated
     */
    const NEW_ACCOUNT_EMAIL_REGISTERED = 'registered';

    /**
     * Welcome email, when password setting is required
     *
     * @deprecated
     */
    const NEW_ACCOUNT_EMAIL_REGISTERED_NO_PASSWORD = 'registered_no_password';

    /**
     * Welcome email, when confirmation is enabled
     *
     * @deprecated
     */
    const NEW_ACCOUNT_EMAIL_CONFIRMATION = 'confirmation';

    /**
     * Confirmation email, when account is confirmed
     *
     * @deprecated
     */
    const NEW_ACCOUNT_EMAIL_CONFIRMED = 'confirmed';

    /**
     * Constants for types of emails to send out.
     * pdl:
     * forgot, remind, reset email templates
     */
    const EMAIL_REMINDER = 'email_reminder';

    /**
     *
     */
    const EMAIL_RESET = 'email_reset';

    /**
     * Configuration path to customer password minimum length
     */
    const XML_PATH_MINIMUM_PASSWORD_LENGTH = 'customer/password/minimum_password_length';

    /**
     * Configuration path to customer password required character classes number
     */
    const XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER = 'customer/password/required_character_classes_number';

    /**
     * @deprecated
     */
    const XML_PATH_RESET_PASSWORD_TEMPLATE = 'customer/password/reset_password_template';

    /**
     * @deprecated
     */
    const MIN_PASSWORD_LENGTH = 6;
    /**
     * @var PsrLogger
     */
    protected $logger;
    /**
     * @var StringHelper
     */
    protected $stringHelper;
    /**
     * @var DataObjectProcessor
     */
    protected $dataProcessor;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var CustomerViewHelper
     */
    protected $customerViewHelper;
    /**
     * @var DateTime
     */
    protected $dateTime;
    /**
     * @var ObjectFactory
     */
    protected $objectFactory;
    /**
     * @var \Magento\Framework\Api\ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;
    /**
     * @var CustomerModel
     */
    protected $customerModel;
    /**
     * @var AuthenticationInterface
     */
    protected $authentication;
    /**
     * @var CustomerHelper
     */
    protected $_customerHelper;
    /**
     * @var CustomerFactory
     */
    private $customerFactory;
    /**
     * @var \Magento\Customer\Api\Data\ValidationResultsInterfaceFactory
     */
    private $validationResultsDataFactory;
    /**
     * @var ManagerInterface
     */
    private $eventManager;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Random
     */
    private $mathRandom;
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;
    /**
     * @var CustomerMetadataInterface
     */
    private $customerMetadataService;
    /**
     * @var Encryptor
     */
    private $encryptor;
    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;
    /**
     * @var ConfigShare
     */
    private $configShare;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;
    /**
     * @var EmailNotificationInterface
     */
    private $emailNotification;
    /**
     * @var \Magento\Eav\Model\Validator\Attribute\Backend
     */
    private $eavValidator;
    /**
     * @var CredentialsValidator
     */
    private $credentialsValidator;
    protected $_transport;

    /**
     * AccountManagement constructor.
     * @param CustomerFactory                   $customerFactory
     * @param ManagerInterface                  $eventManager
     * @param StoreManagerInterface             $storeManager
     * @param Random                            $mathRandom
     * @param Validator                         $validator
     * @param ValidationResultsInterfaceFactory $validationResultsDataFactory
     * @param AddressRepositoryInterface        $addressRepository
     * @param CustomerMetadataInterface         $customerMetadataService
     * @param CustomerRegistry                  $customerRegistry
     * @param PsrLogger                         $logger
     * @param Encryptor                         $encryptor
     * @param ConfigShare                       $configShare
     * @param StringHelper                      $stringHelper
     * @param CustomerRepositoryInterface       $customerRepository
     * @param ScopeConfigInterface              $scopeConfig
     * @param TransportBuilder                  $transportBuilder
     * @param DataObjectProcessor               $dataProcessor
     * @param Registry                          $registry
     * @param CustomerViewHelper                $customerViewHelper
     * @param DateTime                          $dateTime
     * @param CustomerModel                     $customerModel
     * @param ObjectFactory                     $objectFactory
     * @param ExtensibleDataObjectConverter     $extensibleDataObjectConverter
     * @param CredentialsValidator|null         $credentialsValidator
     * @param CustomerHelper                    $customerHelper
     * @param SmsHelper                         $smsHelper
     */
    public function __construct(
        CustomerFactory $customerFactory,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        Random $mathRandom,
        Validator $validator,
        ValidationResultsInterfaceFactory $validationResultsDataFactory,
        AddressRepositoryInterface $addressRepository,
        CustomerMetadataInterface $customerMetadataService,
        CustomerRegistry $customerRegistry,
        PsrLogger $logger,
        Encryptor $encryptor,
        ConfigShare $configShare,
        StringHelper $stringHelper,
        CustomerRepositoryInterface $customerRepository,
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder,
        DataObjectProcessor $dataProcessor,
        Registry $registry,
        CustomerViewHelper $customerViewHelper,
        DateTime $dateTime,
        CustomerModel $customerModel,
        ObjectFactory $objectFactory,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        CustomerHelper $customerHelper,
        Transport $transport,
        CredentialsValidator $credentialsValidator = null
    )
    {
        $this->_customerHelper = $customerHelper;
        $this->_transport = $transport;
        parent::__construct($customerFactory, $eventManager, $storeManager, $mathRandom, $validator, $validationResultsDataFactory, $addressRepository, $customerMetadataService, $customerRegistry, $logger, $encryptor, $configShare, $stringHelper, $customerRepository, $scopeConfig, $transportBuilder, $dataProcessor, $registry, $customerViewHelper, $dateTime, $customerModel, $objectFactory, $extensibleDataObjectConverter, $credentialsValidator);
    }

    /**
     * {@inheritdoc}
     */
    public function resendConfirmation($email, $websiteId = null, $redirectUrl = '')
    {
        parent::resendConfirmation($email, $websiteId, $redirectUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function activate($email, $confirmationKey)
    {
        return parent::activate($email, $confirmationKey);
    }

    /**
     * {@inheritdoc}
     */
    public function activateById($customerId, $confirmationKey)
    {
        return parent::activateById($customerId, $confirmationKey);
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate($username, $password)
    {
        return parent::authenticate($username, $password);
    }
    /**
     * {@inheritdoc}
     */
    public function authenticateLoginWithOtp($username, $password,$validate)
    {
        if(!$validate){
            throw new InvalidEmailOrPasswordException(__('Invalid login or password.'));
        }
        try {
            $customer = $this->_customerHelper->getCustomer($username);
        } catch (NoSuchEntityException $e) {
            throw new InvalidEmailOrPasswordException(__('Invalid login or password.'));
        }

        $customerModel = $this->_customerHelper->getCustomerFactoryObject()->updateData($customer);
        $this->_customerHelper->dispatchEvent('customer_customer_authenticated',['model' => $customerModel, 'password' => $password]);
        $this->_customerHelper->dispatchEvent('customer_data_object_login', ['customer' => $customer]);

        return $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken)
    {
        return parent::validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken);
    }

    /**
     * {@inheritdoc}
     */
    public function initiatePasswordReset($email, $template, $websiteId = null)
    {
        return parent::initiatePasswordReset($email, $template, $websiteId);
    }

    /**
     * {@inheritdoc}
     */
    public function initiatePasswordResetMobile($mobileNumber, $email, $template, $websiteId = null)
    {
        if ($websiteId === null && $this->_customerHelper->getAccountSharingOptions()) {
            $websiteId = $this->_customerHelper->getWebsite()->getId();
        } else {
            $websiteId = false;
        }
        // load customer by email
        $customer = $this->_customerHelper->getCustomerByEmail($email, $websiteId);

        $newPasswordToken = $this->_customerHelper->getUniqueHash();
        $otp = mt_rand(100000, 999999);
        $this->_customerHelper->setCustomerOtpToSession($otp);
        $this->_customerHelper->setCustomerPhoneNumberToSession($mobileNumber);
        $this->_customerHelper->setResetTokenInSession($newPasswordToken);
        if(!$this->_transport->sendSms($mobileNumber, $otp,"reset")){
            $response['status'] = false;
            $response['message'] = __(self::RESEND_FAILURE_MESSAGE);
        }
        $this->changeResetPasswordLinkTokenWithOtp($customer, $newPasswordToken, $otp, $mobileNumber);
        return false;
    }

    /**
     * Change reset password link token
     *
     * Stores new reset password link token
     *
     * @param CustomerInterface $customer
     * @param string            $passwordLinkToken
     * @param                   $otp
     * @param                   $mobileNumber
     * @return bool
     * @throws InputException
     * @throws InputMismatchException
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function changeResetPasswordLinkTokenWithOtp($customer, $passwordLinkToken, $otp, $mobileNumber)
    {
        if (!is_string($passwordLinkToken) || empty($passwordLinkToken) || empty($otp)) {
            throw new InputException(
                __(
                    'Invalid value of "%value" provided for the %fieldName field.',
                    ['value' => $passwordLinkToken, 'fieldName' => 'password reset token']
                )
            );
        }
        if (is_string($passwordLinkToken) && !empty($passwordLinkToken) && is_numeric($otp)) {
            $customerSecure = $this->_customerHelper->retrieveSecureData($customer->getId());
            $customerSecure->setRpToken($passwordLinkToken);
            $customerSecure->setRpTokenCreatedAt((new \DateTime())->format(DateTime::DATETIME_PHP_FORMAT));
            $this->_customerHelper->saveCustomer($customer);
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function resetPassword($email, $resetToken, $newPassword)
    {
        return parent::resetPassword($email, $resetToken, $newPassword);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmationStatus($customerId)
    {
        // load customer by id
        $customer = $this->_customerHelper->getCustomerById($customerId);
        if ($this->isConfirmationRequired($customer)) {
            if (!$customer->getConfirmation()) {
                return self::ACCOUNT_CONFIRMED;
            }
            return self::ACCOUNT_CONFIRMATION_REQUIRED;
        }
        return self::ACCOUNT_CONFIRMATION_NOT_REQUIRED;
    }

    /**
     * Check if accounts confirmation is required in config
     *
     * @param CustomerInterface $customer
     * @return bool
     */
    protected function isConfirmationRequired($customer)
    {
        return parent::isConfirmationRequired($customer);
    }

    /**
     * {@inheritdoc}
     */
    public function createAccount(CustomerInterface $customer, $password = null, $redirectUrl = '')
    {
        if ($password !== null) {
            $this->checkPasswordStrength($password);
            $customerEmail = $customer->getEmail();
            try {
                if (strcasecmp($password, $customerEmail) == 0) {
                    throw new InputException(__('Password cannot be the same as email address.'));
                }
            } catch (InputException $e) {
                throw new LocalizedException(__('Password cannot be the same as email address.'));
            }
            $hash = $this->createPasswordHash($password);
        } else {
            $hash = null;
        }
        return $this->createAccountWithPasswordHash($customer, $hash, $redirectUrl);
    }

    /**
     * Make sure that password complies with minimum security requirements.
     *
     * @param string $password
     * @return void
     * @throws InputException
     */
    protected function checkPasswordStrength($password)
    {
        parent::checkPasswordStrength($password);
    }

    /**
     * Create a hash for the given password
     *
     * @param string $password
     * @return string
     */
    protected function createPasswordHash($password)
    {
        return parent::createPasswordHash($password);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function createAccountWithPasswordHash(CustomerInterface $customer, $hash, $redirectUrl = '')
    {
        return parent::createAccountWithPasswordHash($customer, $hash, $redirectUrl = '');
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultBillingAddress($customerId)
    {
        return parent::getDefaultBillingAddress($customerId);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultShippingAddress($customerId)
    {
        return parent::getDefaultShippingAddress($customerId);
    }

    /**
     * {@inheritdoc}
     */
    public function changePassword($email, $currentPassword, $newPassword)
    {
        return parent::changePassword($email, $currentPassword, $newPassword);
    }

    /**
     * {@inheritdoc}
     */
    public function changePasswordById($customerId, $currentPassword, $newPassword)
    {
        return parent::changePasswordById($customerId, $currentPassword, $newPassword);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(CustomerInterface $customer)
    {
        return parent::validate($customer);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmailAvailable($customerEmail, $websiteId = null)
    {
        return parent::isEmailAvailable($customerEmail, $websiteId);
    }

    /**
     * {@inheritDoc}
     */
    public function isCustomerInStore($customerWebsiteId, $storeId)
    {
        return parent::isCustomerInStore($customerWebsiteId, $storeId);
    }

    /**
     * Check if customer can be deleted.
     *
     * @param int $customerId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException If group is not found
     * @throws LocalizedException
     */
    public function isReadonly($customerId)
    {
        return parent::isReadonly($customerId);
    }

    /**
     * Change reset password link token
     *
     * Stores new reset password link token
     *
     * @param CustomerInterface $customer
     * @param string            $passwordLinkToken
     * @return bool
     * @throws InputException
     */
    public function changeResetPasswordLinkToken($customer, $passwordLinkToken)
    {
        return parent::changeResetPasswordLinkToken($customer, $passwordLinkToken);
    }

    /**
     * Send email with new customer password
     *
     * @param CustomerInterface $customer
     * @return $this
     * @deprecated 100.1.0
     */
    public function sendPasswordReminderEmail($customer)
    {
        return parent::sendPasswordReminderEmail($customer);
    }

    /**
     * Send email with reset password confirmation link
     *
     * @param CustomerInterface $customer
     * @return $this
     * @deprecated 100.1.0
     */
    public function sendPasswordResetConfirmationEmail($customer)
    {
        return parent::sendPasswordResetConfirmationEmail($customer);
    }

    /**
     * Return hashed password, which can be directly saved to database.
     *
     * @param string $password
     * @return string
     */
    public function getPasswordHash($password)
    {
        return parent::getPasswordHash($password);
    }

    /**
     * Check password for presence of required character sets
     *
     * @param string $password
     * @return int
     */
    protected function makeRequiredCharactersCheck($password)
    {
        return parent::makeRequiredCharactersCheck($password);
    }

    /**
     * Retrieve minimum password length
     *
     * @return int
     */
    protected function getMinPasswordLength()
    {
        return parent::getMinPasswordLength();
    }

    /**
     * Send either confirmation or welcome email after an account creation
     *
     * @param CustomerInterface $customer
     * @param string            $redirectUrl
     * @return void
     */
    protected function sendEmailConfirmation(CustomerInterface $customer, $redirectUrl)
    {
        parent::sendEmailConfirmation($customer, $redirectUrl);
    }

    /**
     * Send email with new account related information
     *
     * @param CustomerInterface $customer
     * @param string            $type
     * @param string            $backUrl
     * @param string            $storeId
     * @param string            $sendemailStoreId
     * @return $this
     * @throws LocalizedException
     * @deprecated 100.1.0
     */
    protected function sendNewAccountEmail(
        $customer,
        $type = self::NEW_ACCOUNT_EMAIL_REGISTERED,
        $backUrl = '',
        $storeId = '0',
        $sendemailStoreId = null
    )
    {
        return parent::sendNewAccountEmail(
            $customer,
            $type = self::NEW_ACCOUNT_EMAIL_REGISTERED,
            $backUrl = '',
            $storeId = '0',
            $sendemailStoreId
        );
    }

    /**
     * Send email to customer when his password is reset
     *
     * @param CustomerInterface $customer
     * @return $this
     * @deprecated 100.1.0
     */
    protected function sendPasswordResetNotificationEmail($customer)
    {
        return parent::sendPasswordResetNotificationEmail($customer);
    }

    /**
     * Get either first store ID from a set website or the provided as default
     *
     * @param CustomerInterface $customer
     * @param int|string|null   $defaultStoreId
     * @return int
     * @deprecated 100.1.0
     */
    protected function getWebsiteStoreId($customer, $defaultStoreId = null)
    {
        return parent::getWebsiteStoreId($customer, $defaultStoreId);
    }

    /**
     * @return array
     * @deprecated 100.1.0
     */
    protected function getTemplateTypes()
    {
        return parent::getTemplateTypes();
    }

    /**
     * Send corresponding email template
     *
     * @param CustomerInterface $customer
     * @param string            $template configuration path of email template
     * @param string            $sender configuration path of email identity
     * @param array             $templateParams
     * @param int|null          $storeId
     * @param string            $email
     * @return $this
     * @deprecated 100.1.0
     */
    protected function sendEmailTemplate(
        $customer,
        $template,
        $sender,
        $templateParams = [],
        $storeId = null,
        $email = null
    )
    {
        return parent::sendEmailTemplate(
            $customer,
            $template,
            $sender,
            $templateParams = [],
            $storeId,
            $email
        );
    }

    /**
     * Check whether confirmation may be skipped when registering using certain email address
     *
     * @param CustomerInterface $customer
     * @return bool
     */
    protected function canSkipConfirmation($customer)
    {
        return parent::canSkipConfirmation($customer);
    }

    /**
     * Get address by id
     *
     * @param CustomerInterface $customer
     * @param int               $addressId
     * @return AddressInterface|null
     */
    protected function getAddressById(CustomerInterface $customer, $addressId)
    {
        return parent::getAddressById($customer, $addressId);
    }

    /**
     * Create an object with data merged from Customer and CustomerSecure
     *
     * @param CustomerInterface $customer
     * @return \Magento\Customer\Model\Data\CustomerSecure
     * @deprecated 100.1.0
     */
    protected function getFullCustomerObject($customer)
    {
        return parent::getFullCustomerObject($customer);
    }

    /**
     * Activate a customer account using a key that was sent in a confirmation email.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string                                       $confirmationKey
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws InputException
     * @throws InputMismatchException
     * @throws InvalidTransitionException
     * @throws LocalizedException
     */
    private function activateCustomer($customer, $confirmationKey)
    {
        // check if customer is inactive
        if (!$customer->getConfirmation()) {
            throw new InvalidTransitionException(__('Account already active'));
        }

        if ($customer->getConfirmation() !== $confirmationKey) {
            throw new InputMismatchException(__('Invalid confirmation token'));
        }

        $customer->setConfirmation(null);
        $this->customerRepository->save($customer);
        $this->getEmailNotification()->newAccount($customer, 'confirmed', '', $this->storeManager->getStore()->getId());
        return $customer;
    }

    /**
     * Get email notification
     *
     * @return EmailNotificationInterface
     * @deprecated 100.1.0
     */
    private function getEmailNotification()
    {
        if (!($this->emailNotification instanceof EmailNotificationInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                EmailNotificationInterface::class
            );
        } else {
            return $this->emailNotification;
        }
    }

    /**
     * Change customer password
     *
     * @param CustomerInterface $customer
     * @param string            $currentPassword
     * @param string            $newPassword
     * @return bool true on success
     * @throws InputException
     * @throws InputMismatchException
     * @throws InvalidEmailOrPasswordException
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function changePasswordForCustomer($customer, $currentPassword, $newPassword)
    {
        try {
            $this->getAuthentication()->authenticate($customer->getId(), $currentPassword);
        } catch (InvalidEmailOrPasswordException $e) {
            throw new InvalidEmailOrPasswordException(__('The password doesn\'t match this account.'));
        }
        $customerEmail = $customer->getEmail();
        $this->credentialsValidator->checkPasswordDifferentFromEmail($customerEmail, $newPassword);
        $customerSecure = $this->customerRegistry->retrieveSecureData($customer->getId());
        $customerSecure->setRpToken(null);
        $customerSecure->setRpTokenCreatedAt(null);
        $this->checkPasswordStrength($newPassword);
        $customerSecure->setPasswordHash($this->createPasswordHash($newPassword));
        $this->customerRepository->save($customer);
        return true;
    }

    /**
     * Get authentication
     *
     * @return AuthenticationInterface
     */
    private function getAuthentication()
    {

        if (!($this->authentication instanceof AuthenticationInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Customer\Model\AuthenticationInterface::class
            );
        } else {
            return $this->authentication;
        }
    }

    /**
     * @return Backend
     */
    private function getEavValidator()
    {
        if ($this->eavValidator === null) {
            $this->eavValidator = ObjectManager::getInstance()->get(Backend::class);
        }
        return $this->eavValidator;
    }

    /**
     * Validate the Reset Password Token for a customer.
     *
     * @param int    $customerId
     * @param string $resetPasswordLinkToken
     * @return bool
     * @throws \Magento\Framework\Exception\State\InputMismatchException If token is mismatched
     * @throws \Magento\Framework\Exception\State\ExpiredException If token is expired
     * @throws \Magento\Framework\Exception\InputException If token or customer id is invalid
     * @throws \Magento\Framework\Exception\NoSuchEntityException If customer doesn't exist
     */
    private function validateResetPasswordToken($customerId, $resetPasswordLinkToken)
    {
        if (empty($customerId) || $customerId < 0) {
            throw new InputException(__(
                'Invalid value of "%value" provided for the %fieldName field.',
                ['value' => $customerId, 'fieldName' => 'customerId']
            ));
        }
        if (!is_string($resetPasswordLinkToken) || empty($resetPasswordLinkToken)) {
            $params = ['fieldName' => 'resetPasswordLinkToken'];
            throw new InputException(__('%fieldName is a required field.', $params));
        }

        $customerSecureData = $this->customerRegistry->retrieveSecureData($customerId);
        $rpToken = $customerSecureData->getRpToken();
        $rpTokenCreatedAt = $customerSecureData->getRpTokenCreatedAt();

        if (!Security::compareStrings($rpToken, $resetPasswordLinkToken)) {
            throw new InputMismatchException(__('Reset password token mismatch.'));
        } elseif ($this->isResetPasswordLinkTokenExpired($rpToken, $rpTokenCreatedAt)) {
            throw new ExpiredException(__('Reset password token expired.'));
        }

        return true;
    }

    /**
     * Check if rpToken is expired
     *
     * @param string $rpToken
     * @param string $rpTokenCreatedAt
     * @return bool
     */
    public function isResetPasswordLinkTokenExpired($rpToken, $rpTokenCreatedAt)
    {
        return parent::isResetPasswordLinkTokenExpired($rpToken, $rpTokenCreatedAt);
    }
}
