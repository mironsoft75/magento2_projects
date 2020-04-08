<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Directory\Model;

use Codilar\Directory\Api\CountryRepositoryInterface;
use Codilar\Directory\Api\Data\CountryDataInterfaceFactory;
use Codilar\Directory\Api\Data\CountryInterfaceFactory;
use Codilar\Directory\Api\Data\RegionInterfaceFactory;
use Codilar\Directory\Api\Data\RegionInterface;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Directory\Model\AllowedCountries;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory;
use Magento\Store\Model\ScopeInterface;

class CountryRepository implements CountryRepositoryInterface
{
    /**
     * @var AllowedCountries
     */
    private $allowedCountryModel;
    /**
     * @var CountryInformationAcquirerInterface
     */
    private $countryInformationAcquirer;
    /**
     * @var CountryDataInterfaceFactory
     */
    private $countryDataInterfaceFactory;
    /**
     * @var CountryInterfaceFactory
     */
    private $countryInterfaceFactory;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var CountryFactory
     */
    private $countryFactory;
    /**
     * @var RegionInterfaceFactory
     */
    private $regionInterfaceFactory;

    /**
     * CountryRepository constructor.
     * @param AllowedCountries $allowedCountryModel
     * @param CountryInformationAcquirerInterface $countryInformationAcquirer
     * @param CountryDataInterfaceFactory $countryDataInterfaceFactory
     * @param CountryInterfaceFactory $countryInterfaceFactory
     * @param CollectionFactory $collectionFactory
     * @param CountryFactory $countryFactory
     * @param RegionInterfaceFactory $regionInterfaceFactory
     */
    public function __construct(
        AllowedCountries $allowedCountryModel,
        CountryInformationAcquirerInterface $countryInformationAcquirer,
        CountryDataInterfaceFactory $countryDataInterfaceFactory,
        CountryInterfaceFactory $countryInterfaceFactory,
        CollectionFactory $collectionFactory,
        CountryFactory $countryFactory,
        RegionInterfaceFactory $regionInterfaceFactory
    ) {
        $this->allowedCountryModel = $allowedCountryModel;
        $this->countryInformationAcquirer = $countryInformationAcquirer;
        $this->countryDataInterfaceFactory = $countryDataInterfaceFactory;
        $this->countryInterfaceFactory = $countryInterfaceFactory;
        $this->collectionFactory = $collectionFactory;
        $this->countryFactory = $countryFactory;
        $this->regionInterfaceFactory = $regionInterfaceFactory;
    }

    /**
     * @return \Codilar\Directory\Api\Data\CountryInterface
     */
    public function getCountries()
    {
        $allCountriesCollection = $this->collectionFactory->create();
        $allCountries = [];
        foreach ($allCountriesCollection as $allCouuntry) {
            $allCountries[] = $allCouuntry['country_id'];
        }
        $allowedCountries = $this->allowedCountryModel->getAllowedCountries(ScopeInterface::SCOPE_STORE);
        $disAllowedCountries = array_diff($allCountries, $allowedCountries);

        /** @var  \Codilar\Directory\Api\Data\CountryInterface $data */
        $data = $this->countryInterfaceFactory->create();
        $data->setAllowedCountries($this->getCountryData($allowedCountries))
            ->setDisAllowedCountries($this->getCountryData($disAllowedCountries));
        return $data;
    }

    /**
     * @param $countries
     * @return \Codilar\Directory\Api\Data\CountryDataInterface[]
     */
    protected function getCountryData($countries)
    {
        $countriesArray = [];
        foreach ($countries as $country) {
            /** @var \Codilar\Directory\Api\Data\CountryDataInterface $country */
            $countryData = $this->countryDataInterfaceFactory->create();
            $countryModel = $this->countryFactory->create()->loadByCode($country);
            $hasRegion = false;
            $regions = $countryModel->getRegions();
            $regionsArray = [];
            if ($regions->count() > 0) {
                $hasRegion = true;
                /** @var \Magento\Directory\Model\ResourceModel\Region\Collection $region */
                foreach ($regions as $region) {
                    /** @var \Codilar\Directory\Api\Data\RegionInterface $regionInterface */
                    $regionInterface = $this->regionInterfaceFactory->create();
                    $regionInterface->setRegionId($region->getRegionId())
                        ->setLabel($region->getName());
                    $regionsArray[] = $regionInterface;
                }
            }
            $countryData->setName($countryModel->getName())
                ->setCode($country)
                ->setHasRegion($hasRegion)
                ->setRegions($regionsArray);
            $countriesArray[] = $countryData;
        }
        return $countriesArray;
    }
}
