<?php
/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\NewsLetter\Model;


use Codilar\Api\Helper\Customer;
use Codilar\NewsLetter\Api\Data\ResponseInterface;
use Codilar\NewsLetter\Api\Data\ResponseInterfaceFactory;
use Codilar\NewsLetter\Api\NewsLetterManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\AccountManagementInterface as CustomerAccountManagement;
use Magento\Customer\Model\Session;

class NewsLetterManagement implements NewsLetterManagementInterface
{
    /**
     * @var Customer
     */
    private $customerTokenHelper;
    /**
     * @var ResponseInterfaceFactory
     */
    private $responseInterfaceFactory;
    /**
     * @var SubscriberFactory
     */
    private $subscriberFactory;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var CustomerAccountManagement
     */
    private $accountManagement;
    /**
     * @var Session
     */
    private $customerSession;

    /**
     * NewsLetterManagement constructor.
     * @param Customer $customerTokenHelper
     * @param ResponseInterfaceFactory $responseInterfaceFactory
     * @param SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param StoreManagerInterface $storeManager
     * @param CustomerAccountManagement $accountManagement
     * @param Session $customerSession
     */
    public function __construct(
        Customer $customerTokenHelper,
        ResponseInterfaceFactory $responseInterfaceFactory,
        SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager,
        CustomerAccountManagement $accountManagement,
        Session $customerSession
    )
    {
        $this->customerTokenHelper = $customerTokenHelper;
        $this->responseInterfaceFactory = $responseInterfaceFactory;
        $this->subscriberFactory = $subscriberFactory;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
        $this->accountManagement = $accountManagement;
        $this->customerSession = $customerSession;
    }

    /**
     * @param $email
     * @return \Codilar\NewsLetter\Api\Data\ResponseInterface
     */
    public function subscribe($email)
    {
        /** @var ResponseInterface $response */
        $response = $this->responseInterfaceFactory->create();
        $this->storeManager->setCurrentStore($this->storeManager->getDefaultStoreView()->getId());
        if ($email) {
            try {
                $emailValidatior = new \Zend\Validator\EmailAddress();
                if (!$emailValidatior->isValid($email)) {
                    throw new LocalizedException(__("Please enter a valid email address"));
                }
                $this->validateEmailAvailable($email);
                $response->setStatus(true);
                /** @var Subscriber $subscriber */
                $subscriber = $this->subscriberFactory->create()->loadByEmail($email);
                if ($subscriber->getId()
                    && (int) $subscriber->getSubscriberStatus() === Subscriber::STATUS_SUBSCRIBED
                ) {
                    $response->setMessage("This email address is already subscribed.");
                } else {
                    $subscriber = $this->subscriberFactory->create();
                    $response->setMessage('Thank you for your subscription.');
                }
                try {
                    $customer = $this->customerRepository->get($email);
                    $this->customerSession->setCustomerDataAsLoggedIn($customer);
                    $status = (int) $this->subscriberFactory->create()->subscribe($email);
                } catch (NoSuchEntityException $noSuchEntityException) {
                    $status = (int) $this->subscriberFactory->create()->subscribe($email);
                }

            } catch (LocalizedException $localizedException) {
                $response->setStatus(false)->setMessage($localizedException->getMessage());
            } catch (\Exception $e) {
                $response->setStatus(false)->setMessage("Something went wrong with the subscription.");
            }
        } else {
            $response->setStatus(false)->setMessage("Customer not found!");
        }
        return $response;
    }

    /**
     * @return \Codilar\NewsLetter\Api\Data\ResponseInterface
     */
    public function unsubscribe()
    {
        /** @var ResponseInterface $response */
        $response = $this->responseInterfaceFactory->create();
        $customer = $this->customerTokenHelper->getCustomerIdByToken(true);
        if ($customer) {
            /** @var Subscriber $subscriber */
            $subscriber = $this->subscriberFactory->create();
            $subscriber->loadByEmail($customer->getEmail());
            if ($subscriber->getId()) {
                if ($subscriber->getStatus() == Subscriber::STATUS_UNSUBSCRIBED) {
                    $response->setStatus(true)->setMessage("This email address is already unsubscribed.");
                } else {
                    try {
                        $subscriber->unsubscribe();
                        $response->setStatus(true)->setMessage("You have been unsubscribed.");
                    } catch (LocalizedException $e) {
                        $response->setStatus(false)->setMessage($e->getMessage());
                    }
                }
            } else {
                $response->setStatus(false)->setMessage("Something went wrong with the subscription.");
            }
        } else {
            $response->setStatus(false)->setMessage("Customer not found!");
        }
        return $response;
    }

    /**
     * Validates that the email address isn't being used by a different account.
     *
     * @param string $email
     * @throws LocalizedException
     * @return void
     */
    protected function validateEmailAvailable($email)
    {
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $customer = $this->customerTokenHelper->getCustomerIdByToken(true);
        if ($customer) {
            if ($customer->getEmail() !== $email
                && !$this->accountManagement->isEmailAvailable($email, $websiteId)
            ) {
                throw new LocalizedException(
                    __('This email address is already assigned to another user.')
                );
            }
        }
    }
}