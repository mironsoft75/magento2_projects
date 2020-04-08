<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Customer\Model;


use Codilar\Customer\Api\AddressManagementInterface;
use Codilar\Api\Helper\Customer as CustomerHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Codilar\Customer\Api\Data\AbstractResponseInterface;
use Codilar\Customer\Api\Data\AbstractResponseInterfaceFactory;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory;

class AddressManagement implements AddressManagementInterface
{
    /**
     * @var CustomerHelper
     */
    private $customerHelper;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var AbstractResponseInterfaceFactory
     */
    private $abstractResponseInterfaceFactory;
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;
    /**
     * @var AddressInterfaceFactory
     */
    private $addressInterfaceFactory;
    /**
     * @var RegionInterfaceFactory
     */
    private $regionInterfaceFactory;

    /**
     * AddressManagenent constructor.
     * @param CustomerHelper $customerHelper
     * @param CustomerRepositoryInterface $customerRepository
     * @param AbstractResponseInterfaceFactory $abstractResponseInterfaceFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param AddressInterfaceFactory $addressInterfaceFactory
     * @param RegionInterfaceFactory $regionInterfaceFactory
     */
    public function __construct(
        CustomerHelper $customerHelper,
        CustomerRepositoryInterface $customerRepository,
        AbstractResponseInterfaceFactory $abstractResponseInterfaceFactory,
        AddressRepositoryInterface $addressRepository,
        AddressInterfaceFactory $addressInterfaceFactory,
        RegionInterfaceFactory $regionInterfaceFactory
    )
    {
        $this->customerHelper = $customerHelper;
        $this->customerRepository = $customerRepository;
        $this->abstractResponseInterfaceFactory = $abstractResponseInterfaceFactory;
        $this->addressRepository = $addressRepository;
        $this->addressInterfaceFactory = $addressInterfaceFactory;
        $this->regionInterfaceFactory = $regionInterfaceFactory;
    }

    /**
     * @param \Codilar\Customer\Api\Data\AddressInterface $address
     * @return \Codilar\Customer\Api\Data\AbstractResponseInterface
     */
    public function updateShippingAddress($address)
    {
        try {
            $loggedInCustomerId = $this->customerHelper->getCustomerIdByToken(false);
            if (!$loggedInCustomerId) {
                throw new LocalizedException(__("Customer is not logged in"));
            }
            $loggedInCustomer = $this->customerRepository->getById($loggedInCustomerId);
            $defaultShippingId = $loggedInCustomer->getDefaultShipping();
            if (!$defaultShippingId) {
                $newAddress = $this->addressInterfaceFactory->create();
                $newAddress->setCustomerId($loggedInCustomerId)
                    ->setIsDefaultShipping(true)
                    ->setIsDefaultBilling(true);
            } else {
                $newAddress = $this->addressRepository->getById($defaultShippingId);
            }
            $newAddress->setFirstname($address->getFirstName())
                ->setLastname($address->getLastName())
                ->setCompany($address->getCompany())
                ->setStreet($address->getStreet())
                ->setCity($address->getCity())
                ->setRegion($this->getRegionInterface($address->getState()))
                ->setPostcode($address->getZipcode())
                ->setCountryId($address->getCountry())
                ->setTelephone($address->getPhoneNumber());

            if ($address->getRegionId()) {
                $newAddress->setRegionId($address->getRegionId());
            } else {
                $newAddress->setRegionId(-1);
            }

            $this->addressRepository->save($newAddress);
            $data = [
                'status'    =>  true,
                'message'   =>  __("Shipping Address updated successfully")
            ];
        } catch (LocalizedException $localizedException) {
            $data = [
                'status'    =>  false,
                'message'   =>  $localizedException->getMessage()
            ];
        } catch (\Exception $exception) {
            $data = [
                'status'    =>  false,
                'message'   =>  __("Couldn't save shipping Address")
            ];
        }
        /** @var AbstractResponseInterface $response */
        $response = $this->abstractResponseInterfaceFactory->create();
        $response->setStatus($data['status'])->setMessage($data['message']);
        return $response;
    }

    /**
     * @param string $regionName
     * @return \Magento\Customer\Api\Data\RegionInterface
     */
    protected function getRegionInterface($regionName)
    {
        $region = $this->regionInterfaceFactory->create();
        $region->setRegion($regionName);
        return $region;
    }
}