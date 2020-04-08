<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Helper;

use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Address
{
    /**
     * @var CountryFactory
     */
    private $countryFactory;

    /**
     * Address constructor.
     * @param CountryFactory $countryFactory
     */
    public function __construct(
        CountryFactory $countryFactory
    ) {
        $this->countryFactory = $countryFactory;
    }

    /**
     * @param \Codilar\Checkout\Api\Data\Quote\ShippingAddressInterface|\Codilar\Checkout\Api\Data\Quote\BillingAddressInterface $address
     * @return array
     */
    public function getAddressArray($address)
    {
        $regionName = $this->getRegionName($address->getRegionId(), $address->getCountry());
        if ($regionName == false) {
            $regionId = null;
            $region = $address->getState();
        } else {
            $regionId = $address->getRegionId();
            $region = $regionName;
        }
        $addressArray = [
            'firstname' => $address->getFirstName(),
            'lastname' => $address->getLastName(),
            'street' => $this->getStreetSting($address->getStreet()),
            'city' => $address->getCity(),
            'country_id' => $address->getCountry(),
            'region_id' => $regionId,
            'region' => $region,
            'postcode' => $address->getZipcode(),
            'telephone' => $address->getTelephone(),
            'save_in_address_book' => 0
        ];

        return $addressArray;
    }

    /**
     * @param array $street
     * @return string
     */
    protected function getStreetSting($street)
    {
        $str = '';
        foreach ($street as $item) {
            $str = $str . " " . $item;
        }
        return $str;
    }

    /**
     * @param $id
     * @param $countryCode
     * @return bool
     * @throws NoSuchEntityException
     */
    protected function getRegionName($id, $countryCode)
    {
        $country = $this->countryFactory->create()->loadByCode($countryCode);
        $regions = $country->getRegions();
        if ($regions->count() > 0) {
            foreach ($regions as $region) {
                if ($region->getRegionId() == $id) {
                    return $region->getName();
                }
            }
            throw NoSuchEntityException::singleField('region_id', $id);
        } else {
            return false;
        }
    }
}
