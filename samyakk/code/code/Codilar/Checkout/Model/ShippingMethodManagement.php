<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model;


use Codilar\Checkout\Api\ShippingMethodManagementInterface;
use Codilar\Api\Helper\Customer;
use Codilar\Checkout\Helper\Address;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\ShippingMethodManagementInterface as ShippingMethodInterface;
use Magento\Quote\Api\Data\EstimateAddressInterface;
use Codilar\Checkout\Api\Data\ShippingMethodInterfaceFactory as ShippingMethodResponseInterface;
use Codilar\Checkout\Api\Data\CartInterface;
use Codilar\Checkout\Api\Data\CartInterfaceFactory;

class ShippingMethodManagement implements ShippingMethodManagementInterface
{
    /**
     * @var Customer
     */
    private $customerHelper;
    /**
     * @var ShippingMethodInterface
     */
    private $shippingMethodManagement;
    /**
     * @var EstimateAddressInterface
     */
    private $estimateAddress;
    /**
     * @var ShippingMethodResponseInterface
     */
    private $shippingMethodResponse;
    /**
     * @var Address
     */
    private $addressHelper;
    /**
     * @var CartInterfaceFactory
     */
    private $cartFactory;

    /**
     * ShippingMethodManagement constructor.
     * @param Customer $customerHelper
     * @param ShippingMethodInterface $shippingMethodManagement
     * @param EstimateAddressInterface $estimateAddress
     * @param ShippingMethodResponseInterface $shippingMethodResponse
     * @param Address $addressHelper
     * @param CartInterfaceFactory $cartFactory
     */
    public function __construct(
        Customer $customerHelper,
        ShippingMethodInterface $shippingMethodManagement,
        EstimateAddressInterface $estimateAddress,
        ShippingMethodResponseInterface $shippingMethodResponse,
        Address $addressHelper,
        CartInterfaceFactory $cartFactory
    )
    {
        $this->customerHelper = $customerHelper;
        $this->shippingMethodManagement = $shippingMethodManagement;
        $this->estimateAddress = $estimateAddress;
        $this->shippingMethodResponse = $shippingMethodResponse;
        $this->addressHelper = $addressHelper;
        $this->cartFactory = $cartFactory;
    }

    /**
     * @param \Codilar\Checkout\Api\Data\Quote\ShippingAddressInterface $shippingAddress
     * @return \Codilar\Checkout\Api\Data\ShippingMethodInterface[]
     */
    public function getShippingMethods($shippingAddress)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->customerHelper->getActiveQuote();
        $quote->getShippingAddress()->addData($this->addressHelper->getAddressArray($shippingAddress));
        $quote->getShippingAddress()
            ->setCollectShippingRates(true)
            ->collectShippingRates();
        try {
            $quote->save();
        } catch (\Exception $e) {
        }
        $quote->collectTotals();
        $shippingMethods = $this->shippingMethodManagement->estimateByAddress($quote->getId(), $this->getShippingModel($shippingAddress));
        $shippingMethodsResponse = [];
        /** @var \Magento\Quote\Api\Data\ShippingMethodInterface $shippingMethod */
        foreach ($shippingMethods as $shippingMethod) {
            $shipping = $this->shippingMethodResponse->create();
            $shippingMethodsResponse[] = $shipping->setAmount($shippingMethod->getAmount())
                ->setCarrierCode($shippingMethod->getCarrierCode())
                ->setCarrierTitle($shippingMethod->getCarrierTitle())
                ->setMethodCode($shippingMethod->getMethodCode())
                ->setMethodTitle($shippingMethod->getMethodTitle());
        }
        return $shippingMethodsResponse;
    }


    /**
     * @param \Codilar\Checkout\Api\Data\Quote\ShippingAddressInterface $shippingAddress
     * @return EstimateAddressInterface
     */
    protected function getShippingModel($shippingAddress) {
        return $this->estimateAddress->setCountryId($shippingAddress->getCountry())
            ->setPostcode($shippingAddress->getZipcode());
    }

    /**
     * @param string $shippingMethod
     * @return \Codilar\Checkout\Api\Data\CartInterface
     * @throws NoSuchEntityException
     */
    public function setShippingMethod($shippingMethod)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->customerHelper->getActiveQuote();

        $quote->getShippingAddress()
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($shippingMethod);
        $quote->save();
        $quote->collectTotals()->save();

        /** @var CartInterface $cart */
        $cart = $this->cartFactory->create();
        return $cart->init($quote);
    }
}