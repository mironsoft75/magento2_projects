<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 20/11/18
 * Time: 11:42 AM
 */

namespace Codilar\Videostore\Block\Cart;

use Codilar\Videostore\Model\VideostoreCartRepository;
use Magento\Directory\Model\Country;
use Magento\Directory\Model\ResourceModel\Region\Collection as RegionCollection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class Index extends  Template
{
    protected $_videostoreCartRepository;
    /**
     * @var Country
     */
    private $countryCollection;
    /**
     * @var RegionCollection
     */
    private $regionCollection;

    /**
     * Index constructor.
     * @param Template\Context $context
     * @param VideostoreCartRepository $videostoreCartRepository
     * @param Country $countryCollection
     * @param RegionCollection $regionCollection
     * @param array $data
     */
    public function __construct(Template\Context $context,
                                VideostoreCartRepository $videostoreCartRepository,
                                Country $countryCollection,
                                RegionCollection $regionCollection,
                                array $data = [])
    {
        $this->countryCollection = $countryCollection;
        $this->regionCollection = $regionCollection;
        $this->_videostoreCartRepository = $videostoreCartRepository;
        parent::__construct($context, $data);

    }

    /**
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function hasProducts()
    {
        return count($this->_videostoreCartRepository->getProducts())? true: false;
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    public function getProducts(){
        try {
            return $this->_videostoreCartRepository->getProducts();
        } catch (NoSuchEntityException $e) {
            throw new LocalizedException(__("No Products Available"));
        }
    }

    /**
     * @return string
     */
    public function getDeleteFormAction()
    {
        return $this->getUrl('videostore/cart/delete');
    }

    /**
     * @return string
     */
    public function getRequestFormAction()
    {
        return $this->getUrl('videostore/cart/request');
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        $countryCode = array("IN", "US");
        $country = array("India", "USA");
        for ($i = 0; $i < sizeof($countryCode); $i++) {
            $regionCollection = $this->countryCollection->loadByCode($countryCode[$i])->getRegions();
            foreach ($regionCollection as $region) {
                $regions[$i][] = $region["default_name"];
            }
            $countries[$i]["country"] = [$country[$i]];
            $countries[$i]["state"] = [$regions[$i]];
        }

        return $countries;
    }

    /**
     * @param string $countryId
     * @return array
     */
    public function getStates($countryId)
    {
        $states = $this->regionCollection->addFieldToFilter('country_id', $countryId)->toOptionArray();
        $stateList = array();
        foreach ($states as $state){
            if($state['value']){
                $temp['value'] = $state['label'];
                $temp['label'] = $state['label'];
                array_push($stateList, $temp);
            }
        }
        return $stateList;
    }
}