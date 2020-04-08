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
use Magento\Customer\Model\ResourceModel\Customer\Collection as CustomerCollection;
use Magento\Store\Model\StoreManager;;

class CustomerMap implements MigrationTypeInterface
{

    /**
     * @var CustomerCollection
     */
    private $customerCollection;
    /**
     * @var StoreManager
     */
    private $storeManager;
    /**
     * @var MigrationCsv
     */
    private $migrationCsv;

    /**
     * Customer constructor.
     * @param CustomerCollection $customerCollection
     * @param StoreManager $storeManager
     * @param MigrationCsv $migrationCsv
     */
    public function __construct(
        CustomerCollection $customerCollection,
        StoreManager $storeManager,
        MigrationCsv $migrationCsv
    )
    {
        $this->customerCollection = $customerCollection;
        $this->storeManager = $storeManager;
        $this->migrationCsv = $migrationCsv;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function startMigration()
    {
        $customerDataFileName = "customer_data_migration_".date('dmY').".csv";
        $customerIdFileName = "customer_id_migration_".date('dmY').".csv";
        $customerData = [];
        $customerData[] = $this->getDataHerders();
        $customerId = [];
        $customerId[] = $this->getIdHeaders();
        $customers = $this->customerCollection->addAttributeToSelect('*');
        /** @var \Magento\Customer\Model\Customer $customer */
        foreach ($customers as $customer) {
            $customerData[] = [
                $this->storeManager->getWebsite($customer->getWebsiteId())->getCode(),
                $customer->getWebsiteId(),
                $this->storeManager->getStore($customer->getStoreId())->getCode(),
                $customer->getStoreId(),
                $customer->getStore()->getName(),
                $customer->getGroupId(),
                $customer->getEmail(),
                $customer->getFirstname(),
                $customer->getLastname()
            ];
            $customerId[] = [
                $customer->getId(),
                $customer->getEmail()
            ];
        }
        $customerDataFileName = $this->migrationCsv->generateCsv($customerData, $customerDataFileName);
        $customerIdFileName = $this->migrationCsv->generateCsv($customerId, $customerIdFileName);
        return $customerDataFileName ." and " .$customerIdFileName;
    }

    protected function getDataHerders(){
        return [
            "_website",
            "website_id",
            "_store",
            "store_id",
            "created_in",
            "group_id",
            "email",
            "firstname",
            "lastname",
        ];
    }

    public function getIdHeaders() {
        return [
            "old_id",
            "email"
        ];
    }
}