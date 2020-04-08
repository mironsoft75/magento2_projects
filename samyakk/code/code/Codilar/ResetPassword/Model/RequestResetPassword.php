<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\ResetPassword\Model;


use Codilar\ResetPassword\Helper\EmailHelper;
use Codilar\ResetPassword\Api\Data\ResetPasswordInterface;
use Codilar\ResetPassword\Api\RequestResetPasswordInterface;
use Codilar\ResetPassword\Api\ResetPasswordRepositoryInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Codilar\Customer\Api\Data\AbstractResponseInterface;
use Codilar\Customer\Api\Data\AbstractResponseInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Integration\Api\CustomerTokenServiceInterface;

class RequestResetPassword implements RequestResetPasswordInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var DateTime
     */
    private $dateTime;
    /**
     * @var EncryptorInterface
     */
    private $encryptor;
    /**
     * @var ResetPasswordInterface
     */
    private $resetPassword;
    /**
     * @var ResetPasswordRepositoryInterface
     */
    private $passwordRepository;
    /**
     * @var AbstractResponseInterfaceFactory
     */
    private $abstractResponseInterfaceFactory;
    /**
     * @var EmailHelper
     */
    private $emailHelper;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var AccountManagementInterface
     */
    private $accountManagement;
    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;
    /**
     * @var CustomerTokenServiceInterface
     */
    private $customerTokenService;

    /**
     * RequestResetPassword constructor.
     * @param CustomerRepositoryInterface $customerRepository
     * @param DateTime $dateTime
     * @param EncryptorInterface $encryptor
     * @param ResetPasswordInterface $resetPassword
     * @param ResetPasswordRepositoryInterface $passwordRepository
     * @param AbstractResponseInterfaceFactory $abstractResponseInterfaceFactory
     * @param EmailHelper $emailHelper
     * @param StoreManagerInterface $storeManager
     * @param Config $config
     * @param AccountManagementInterface $accountManagement
     * @param CustomerRegistry $customerRegistry
     * @param CustomerTokenServiceInterface $customerTokenService
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        DateTime $dateTime,
        EncryptorInterface $encryptor,
        ResetPasswordInterface $resetPassword,
        ResetPasswordRepositoryInterface $passwordRepository,
        AbstractResponseInterfaceFactory $abstractResponseInterfaceFactory,
        EmailHelper $emailHelper,
        StoreManagerInterface $storeManager,
        Config $config,
        AccountManagementInterface $accountManagement,
        CustomerRegistry $customerRegistry,
        CustomerTokenServiceInterface $customerTokenService
    )
    {
        $this->customerRepository = $customerRepository;
        $this->dateTime = $dateTime;
        $this->encryptor = $encryptor;
        $this->resetPassword = $resetPassword;
        $this->passwordRepository = $passwordRepository;
        $this->abstractResponseInterfaceFactory = $abstractResponseInterfaceFactory;
        $this->emailHelper = $emailHelper;
        $this->storeManager = $storeManager;
        $this->config = $config;
        $this->accountManagement = $accountManagement;
        $this->customerRegistry = $customerRegistry;
        $this->customerTokenService = $customerTokenService;
    }

    /**
     * @param \Codilar\ResetPassword\Api\Data\RequestResetPasswordDataInterface $data
     * @return \Codilar\Customer\Api\Data\AbstractResponseInterface
     */
    public function resetPassword($data)
    {
        try {
            /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
            $customer = $this->customerRepository->get($data->getEmail(), 1);
            $token = $this->encryptor->hash($data->getEmail() . $this->dateTime->date());
            try{
                $passwordReset = $this->passwordRepository->load($data->getEmail(), 'customer_email');
            } catch (NoSuchEntityException $exception) {
                $passwordReset = $this->passwordRepository->create();
            }
            $passwordReset->setCustomerId($customer->getId())
                ->setResetToken($token)
                ->setCustomerEmail($data->getEmail())
                ->setCreatedAt($this->dateTime->date());
            $this->passwordRepository->save($passwordReset);
            $baseUrl = $this->storeManager->getStore()->getBaseUrl();
            $url = $baseUrl."resetpassword?id=".$token;
            $this->emailHelper->sendRequestMail($customer, $url);
        } catch (NoSuchEntityException $e) {
        } catch (LocalizedException $e) {
        }
        $data = $this->getResponseData(true, "Password reset link will be sent to your email");
        /** @var AbstractResponseInterface $response */
        $response = $this->abstractResponseInterfaceFactory->create();
        $response->setStatus($data['status'])->setMessage($data['message']);
        return $response;
    }

    /**
     * @param string $token
     * @return AbstractResponseInterface
     */
    public function checkToken($token) {
        try {
            $passwordReset = $this->passwordRepository->load($token, 'reset_token');
            $createdTime = strtotime($passwordReset->getCreatedAt());
            $currentTime = strtotime($this->dateTime->date());
            $interval  = abs($createdTime - $currentTime);
            $minutesDifference  = round($interval / 60);
            if ($minutesDifference <= $this->config->getExprieTime()) {
                $data = $this->getResponseData(true, "Valid Reset Password Request");
            } else {
                $this->passwordRepository->delete($passwordReset);
                $data = $this->getResponseData(false, "Password reset request timeout, Try again");
            }
        } catch (NoSuchEntityException $e) {
            $data = $this->getResponseData(false, "Password reset request timeout, Try again");
        }
        /** @var AbstractResponseInterface $response */
        $response = $this->abstractResponseInterfaceFactory->create();
        $response->setStatus($data['status'])->setMessage($data['message']);
        return $response;
    }


    /**
     * @param \Codilar\ResetPassword\Api\Data\ResetPasswordDataInterface $data
     * @return AbstractResponseInterface
     */
    public function confirmResetPassword($data)
    {
        try {
            $passwordReset = $this->passwordRepository->load($data->getToken(), 'reset_token');
            try {
                $customer = $this->customerRepository->getById($passwordReset->getCustomerId());
                $response = $this->checkToken($data->getToken());
                if (!$response->getStatus()) {
                    $data = $response;
                } else {
                    if ($data->getNewPassword() == $data->getConfirmNewPassword()) {
                        $customerSecure = $this->customerRegistry->retrieveSecureData($customer->getId());
                        $customerSecure->setRpToken(null);
                        $customerSecure->setRpTokenCreatedAt(null);
                        $customerSecure->setPasswordHash($this->createPasswordHash($data->getNewPassword()));
                        $this->customerRepository->save($customer);
                        $data = $this->getResponseData(true, "Password Reseted Succefully");
                        $this->passwordRepository->delete($passwordReset);
                        try {
                            $this->customerTokenService->revokeCustomerAccessToken($customer->getId());
                        } catch (LocalizedException $e) {
                        }
                        $this->emailHelper->sendConfirmEmail($customer);
                    } else {
                        $data = $this->getResponseData(false, "Passwords did not match each other");
                    }
                }
            } catch (NoSuchEntityException $e) {
                $data = $this->getResponseData(false, "Password reset request timeout, Try again");
            } catch (LocalizedException $e) {
                $data = $this->getResponseData(false, $e->getMessage());
            }
        } catch (NoSuchEntityException $e) {
            $data = $this->getResponseData(false, "Password reset request timeout, Try again");
        }
        /** @var AbstractResponseInterface $response */
        $response = $this->abstractResponseInterfaceFactory->create();
        $response->setStatus($data['status'])->setMessage($data['message']);
        return $response;
    }

    /**
     * @param boolean $status
     * @param string $message
     * @return array
     */
    protected function getResponseData($status, $message){
        $data = [
            'status'    =>  $status,
            'message'   =>  __($message)
        ];
        return $data;
    }

    /**
     * @param string $password
     * @return string
     */
    protected function createPasswordHash($password) {
        return $this->encryptor->getHash($password, true);
    }
}