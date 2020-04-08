<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Customer\Model;

use Codilar\Customer\Api\AccountManagementInterface;
use Codilar\Customer\Helper\Firebase as FirebaseHelper;
use Codilar\Customer\Api\Data\LoginResponseInterface;
use Codilar\Customer\Api\Data\LoginResponseInterfaceFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Integration\Model\Oauth\TokenFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;

class AccountManagement implements AccountManagementInterface
{
    /**
     * @var FirebaseHelper
     */
    private $firebaseHelper;
    /**
     * @var LoginResponseInterfaceFactory
     */
    private $loginResponseFactory;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    private $accountManagement;
    /**
     * @var TokenFactory
     */
    private $tokenFactory;
    /**
     * @var CustomerInterfaceFactory
     */
    private $customerFactory;
    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * AccountManagement constructor.
     * @param FirebaseHelper $firebaseHelper
     * @param LoginResponseInterfaceFactory $loginResponseFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Api\AccountManagementInterface $accountManagement
     * @param TokenFactory $tokenFactory
     * @param CustomerInterfaceFactory $customerFactory
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        FirebaseHelper $firebaseHelper,
        LoginResponseInterfaceFactory $loginResponseFactory,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        TokenFactory $tokenFactory,
        CustomerInterfaceFactory $customerFactory,
        EncryptorInterface $encryptor
    )
    {
        $this->firebaseHelper = $firebaseHelper;
        $this->loginResponseFactory = $loginResponseFactory;
        $this->customerRepository = $customerRepository;
        $this->accountManagement = $accountManagement;
        $this->tokenFactory = $tokenFactory;
        $this->customerFactory = $customerFactory;
        $this->encryptor = $encryptor;
    }

    /**
     * @param string $token
     * @param string $provider
     * @return \Codilar\Customer\Api\Data\LoginResponseInterface
     */
    public function socialLogin($token, $provider = 'firebase')
    {
        /** @var LoginResponseInterface $response */
        $response = $this->loginResponseFactory->create();
        try {
            $customer = $this->authenticate($token, $provider);
            $token = $this->tokenFactory->create()->createCustomerToken($customer->getId())->getToken();
            $response->setStatus(true)
                ->setMessage(__("Logged in successfully"))
                ->setToken($token);
        } catch (LocalizedException $localizedException) {
            $response
                ->setStatus(false)
                ->setMessage(__($localizedException->getMessage()))
                ->setToken('');
        }
        return $response;
    }

    /**
     * @param string $token
     * @param string $provider
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function authenticate($token, $provider)
    {
        switch ($provider) {
            case 'firebase':
                $user = $this->firebaseHelper->getUserByToken($token);
                $email = $user->email ?: $user->providerData[0]->email;
                try {
                    if (!$email) {
                        throw new LocalizedException(__("Couldn't retrieve customer's email"));
                    }
                    return $this->customerRepository->get($email);
                } catch (NoSuchEntityException $e) {
                    $name = $user->displayName;
                    if (!strlen($name)) {
                        throw new LocalizedException(__("Couldn't retrieve customer's name"));
                    }
                    $name = explode(' ', $name, 2);
                    $firstname = $name[0];
                    if (count($name) >= 2) {
                        $lastname = $name[1];
                    } else {
                        $lastname = $firstname;
                    }
                    return $this->createCustomer($firstname, $lastname, $email);
                }
                break;
            default:
                throw NoSuchEntityException::singleField("Provider", $provider);
        }
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws LocalizedException
     */
    protected function createCustomer($firstname, $lastname, $email)
    {
        $customer = $this->customerFactory->create()
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email);
        return $this->accountManagement->createAccount($customer, $this->encryptor->getHash($email, true));
    }
}
