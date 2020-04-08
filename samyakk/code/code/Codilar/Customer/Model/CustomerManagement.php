<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Customer\Model;


use Codilar\Api\Helper\Customer as CustomerHelper;
use Codilar\Customer\Api\CustomerManagementInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Codilar\Customer\Api\Data\AbstractResponseInterface;
use Codilar\Customer\Api\Data\AbstractResponseInterfaceFactory;

class CustomerManagement implements CustomerManagementInterface
{
    /**
     * @var CustomerHelper
     */
    private $customerHelper;
    /**
     * @var AbstractResponseInterfaceFactory
     */
    private $abstractResponseFactory;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var AccountManagementInterface
     */
    private $accountManagement;

    /**
     * CustomerManagement constructor.
     * @param CustomerHelper $customerHelper
     * @param AbstractResponseInterfaceFactory $abstractResponseFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $accountManagement
     */
    public function __construct(
        CustomerHelper $customerHelper,
        AbstractResponseInterfaceFactory $abstractResponseFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $accountManagement
    )
    {
        $this->customerHelper = $customerHelper;
        $this->abstractResponseFactory = $abstractResponseFactory;
        $this->customerRepository = $customerRepository;
        $this->accountManagement = $accountManagement;
    }

    /**
     * @param \Codilar\Customer\Api\Data\CustomerInterface $customer
     * @return \Codilar\Customer\Api\Data\AbstractResponseInterface
     */
    public function updateCustomerDetails($customer)
    {
        try {
            $loggedInCustomerId = $this->customerHelper->getCustomerIdByToken(false);
            if (!$loggedInCustomerId) {
                throw new LocalizedException(__("Customer is not logged in"));
            }
            $loggedInCustomer = $this->customerRepository->getById($loggedInCustomerId);
            $fields = ["firstname", "lastname", "email"];
            foreach ($fields as $field) {
                $this->updateFieldValueIfExists($loggedInCustomer, $customer, $field);
            }
            $this->customerRepository->save($loggedInCustomer);
            if ($customer->getIsPasswordChanged()) {
                if ($customer->getNewPassword() !== $customer->getRepeatNewPassword()) {
                    throw new LocalizedException(__("Password and repeat password don't match"));
                }
                $this->accountManagement->changePassword($loggedInCustomer->getEmail(), $customer->getCurrentPassword(), $customer->getNewPassword());
                $this->customerHelper->deleteCustomerToken($loggedInCustomerId);
            }
            $data = [
                'status'    =>  true,
                'message'   =>  __("Details updated successfully")
            ];
        } catch (LocalizedException $localizedException) {
            $data = [
                'status'    =>  false,
                'message'   =>  $localizedException->getMessage()
            ];
        } catch (\Exception $exception) {
            $data = [
                'status'    =>  false,
                'message'   =>  __("Couldn't save customer details")
            ];
        }
        /** @var AbstractResponseInterface $response */
        $response = $this->abstractResponseFactory->create();
        $response->setStatus($data['status'])->setMessage($data['message']);
        return $response;
    }

    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $loggedInCustomer
     * @param \Codilar\Customer\Api\Data\CustomerInterface $newCustomerData
     * @param string $field
     */
    protected function updateFieldValueIfExists($loggedInCustomer, $newCustomerData, $field)
    {
        $value = $newCustomerData->getData($field);
        if ($value && strlen($value) > 0) {
            switch ($field) {
                case "firstname":
                    $loggedInCustomer->setFirstname($value);
                    break;
                case "lastname":
                    $loggedInCustomer->setLastname($value);
                    break;
                case "email":
                    $loggedInCustomer->setEmail($value);
                    break;
            }
        }
    }

}