<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 20/11/18
 * Time: 11:42 AM
 */

namespace Codilar\Videostore\Block\Cart;

use Codilar\Videostore\Model\VideostoreCartRepository;
use Magento\Directory\Model\ResourceModel\Country\Collection;
use Magento\Directory\Model\ResourceModel\Region\Collection as RegionCollection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Codilar\Videostore\CustomerData\Videocart;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;


class Index extends Template
{
    protected $_videostoreCartRepository;
    /**
     * @var CollectionFactory
     */
    private $CountryCollection;
    /**
     * @var RegionCollection
     */

    private $regionCollection;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    protected $videocart;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Index constructor.
     * @param Template\Context $context
     * @param VideostoreCartRepository $videostoreCartRepository
     * @param Collection $CountryCollection
     * @param RegionCollection $regionCollection
     * @param Videocart $videocart
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Codilar\Videostore\Model\VideostoreCartRepository $videostoreCartRepository,
        Collection $CountryCollection,
        RegionCollection $regionCollection,
        Videocart $videocart,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        array $data = [])
    {
        $this->_videostoreCartRepository = $videostoreCartRepository;
        parent::__construct($context, $data);
        $this->CountryCollection = $CountryCollection;
        $this->regionCollection = $regionCollection;
        $this->_storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function hasProducts()
    {
        return count($this->_videostoreCartRepository->getProducts()) ? true : false;
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    public function getProducts()
    {
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
        $countries = $this->CountryCollection->toOptionArray();
        $countryList = array();
        foreach ($countries as $country) {
            if ($country['value']) {
                $temp['value'] = $country['value'];
                $temp['label'] = $country['label'];
                array_push($countryList, $temp);
            }
        }
        return $countryList;
    }

    /**
     * @param string $countryId
     * @return array
     */
    public function getStates($countryId)
    {
        $states = $this->regionCollection->addFieldToFilter('country_id', $countryId)->toOptionArray();
        $stateList = array();
        foreach ($states as $state) {
            if ($state['value']) {
                $temp['value'] = $state['label'];
                $temp['label'] = $state['label'];
                array_push($stateList, $temp);
            }
        }
        return $stateList;
    }
    //delete this

    /**
     * @return array|mixed
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerProductDetails()
    {
        return $this->_videostoreCartRepository->getProducts();

    }

    /**
     * @return mixed
     */
    public function getVideoCart()
    {
        try {
            return $this->videocart->getSectionData();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @param $attributeValue
     * @return string
     */
    public function getAttributeValues($attributeValue)
    {
        try {
            if ($attributeValue) {
                if (is_array($attributeValue)) {
                    return implode(",", $attributeValue);
                } else {
                    return $attributeValue;
                }
            } else {
                return "-";
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

    }
}