<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Migration\Model;

use Codilar\Migration\Helper\MigrationCsv;
use Codilar\Migration\Model\Migration\Types\MigrationTypeInterface;
use Magento\Customer\Api\CustomerRepositoryInterfaceFactory;
use Magento\Customer\Model\ResourceModel\Address\CollectionFactory;
use Magento\Store\Model\StoreManager;

class Address implements MigrationTypeInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var CustomerRepositoryInterfaceFactory
     */
    private $customerRepositoryInterfaceFactory;
    /**
     * @var StoreManager
     */
    private $storeManager;
    /**
     * @var MigrationCsv
     */
    private $migrationCsv;

    /**
     * Address constructor.
     * @param CollectionFactory $collectionFactory
     * @param CustomerRepositoryInterfaceFactory $customerRepositoryInterfaceFactory
     * @param StoreManager $storeManager
     * @param MigrationCsv $migrationCsv
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        CustomerRepositoryInterfaceFactory $customerRepositoryInterfaceFactory,
        StoreManager $storeManager,
        MigrationCsv $migrationCsv
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerRepositoryInterfaceFactory = $customerRepositoryInterfaceFactory;
        $this->storeManager = $storeManager;
        $this->migrationCsv = $migrationCsv;
    }

    /**
     * @return string
     */
    public function startMigration()
    {
        $addressFileName = "customer_address_migration_".date('dmY').".csv";
        $addressData = [];
        $addressData[] = $this->getAddressHeader();
        $addressCollection = $this->collectionFactory->create();
        /** @var \Magento\Customer\Model\Address $address */
        foreach ($addressCollection as $address) {
            /** @var \Magento\Customer\Model\Customer $customer */
            $customer = $address->getCustomer();
            $isDefaultBilling = ($customer->getDefaultBilling() == $address->getId()) ? 1 : 0;
            $isDefaultShipping = ($customer->getDefaultShipping() == $address->getId()) ? 1 : 0;
            $addressData[] = [
                $this->storeManager->getWebsite($customer->getWebsiteId())->getCode(),
                $customer->getEmail(),
                1,
                $address->getCompany(),
                $address->getFax(),
                $address->getFirstname(),
                $address->getLastname(),
                $address->getMiddlename(),
                $address->getPostcode(),
                $address->getPrefix(),
                $address->getRegion(),
                $address->getRegionId(),
                $address->getStreet(),
                $address->getSuffix(),
                $address->getTelephone(),
                $isDefaultBilling,
                $isDefaultShipping
            ];
        }
        $customerAddressFileName = $this->migrationCsv->generateCsv($addressData, $addressFileName);
        return $customerAddressFileName;
    }

    public function getAddressHeader()
    {
        return [
            "_website",
            "email",
            "_entity_id",
            "city",
            "company",
            "country_id",
            "fax",
            "firstname",
            "lastname",
            "middlename",
            "postcode",
            "prefix",
            "region",
            "region_id",
            "street",
            "suffix",
            "telephone",
            "_address_default_billing_",
            "_address_default_shipping_"
        ];
    }

    protected function getFullStreet() {

    }
}
