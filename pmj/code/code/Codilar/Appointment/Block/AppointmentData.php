<?php

namespace Codilar\Appointment\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Directory\Block\Data;
use Magento\Directory\Model\Country;

/**
 * Class AppointmentData
 * @package Codilar\Appointment\Block
 */
class AppointmentData extends Template
{
    protected $_directoryBlock;
    protected $_storeManager;
    protected $_country;

    /**
     * AppointmentData constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Data $directoryBlock
     * @param Country $country
     * @param array $data
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Data $directoryBlock,
        Country $country,
        array $data = []
    )
    {
        $this->_storeManager = $storeManager;
        $this->_directoryBlock = $directoryBlock;
        $this->_country = $country;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseUrls()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return string
     */
    public function getCountriesData()
    {
        return $this->_directoryBlock->getCountryHtmlSelect();
    }

    /**
     * @return string
     */
    public function getRegionData()
    {
        return $this->_directoryBlock->getRegionHtmlSelect();
    }

    /**
     * @return mixed
     */
    public function getRegionsOfCountry()
    {
        $countryCode = array("IN", "US");
        $country = array("India", "USA");
        for ($i = 0; $i < sizeof($countryCode); $i++) {
            $regionCollection = $this->_country->loadByCode($countryCode[$i])->getRegions();
            foreach ($regionCollection as $region) {
                $regions[$i][] = $region["default_name"];
            }
            $countries[$i]["country"] = [$country[$i]];
            $countries[$i]["state"] = [$regions[$i]];
        }

        return $countries;
    }
}