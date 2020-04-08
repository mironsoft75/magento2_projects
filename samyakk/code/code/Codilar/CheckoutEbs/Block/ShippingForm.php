<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25/9/19
 * Time: 3:02 PM
 */

namespace Codilar\CheckoutEbs\Block;

use Magento\Framework\View\Element\Template;
use Magento\Directory\Model\CountryFactory;


/**
 * Class ShippingForm
 *
 * @package Codilar\CheckoutEbs\Block
 */
class ShippingForm extends Template
{
    /**
     * Form
     *
     * @var \Codilar\CheckoutEbs\Block\Form
     */
    private $_orderForm;
    /**
     * CountryFactory
     *
     * @var CountryFactory
     */
    private $_countryFactory;

    /**
     * ShippingForm constructor.
     *
     * @param Template\Context $context
     * @param Form $form
     * @param CountryFactory $countryFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Form $form,
        CountryFactory $countryFactory,
        array $data = []
    )
    {
        $this->_orderForm = $form;
        $this->_countryFactory = $countryFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get ShippingAddress
     *
     * @return mixed
     */
    public function getOrderShippingAddress()
    {
        $order = $this->_orderForm->getOrder();
        /**
         * OrderInterface
         *
         * @var \Magento\Sales\Api\Data\OrderInterface $order
         */
        return $order->getShippingAddress();
    }

    /**
     * Get DeliveryName
     *
     * @return string
     */
    public function getDeliveryName()
    {
        /**
         * OrderAddressInterface
         *
         * @var \Magento\Sales\Api\Data\OrderAddressInterface $shippingAddress
         */
        $shippingAddress = $this->getOrderShippingAddress();

        return $shippingAddress->getFirstname() . " " .
            $shippingAddress->getLastname();
    }

    /**
     * Get DeliveryAddress
     *
     * @return string
     */
    public function getDeliveryAddress()
    {
        /**
         * OrderAddressInterface
         *
         * @var \Magento\Sales\Api\Data\OrderAddressInterface $shippingAddress
         */
        $shippingAddress = $this->getOrderShippingAddress();
        return implode(" ", $shippingAddress->getStreet());

    }

    /**
     * Get DeliveryCity
     *
     * @return string
     */
    public function getDeliveryCity()
    {
        /**
         * OrderAddressInterface
         *
         * @var \Magento\Sales\Api\Data\OrderAddressInterface $shippingAddress
         */
        $shippingAddress = $this->getOrderShippingAddress();
        return $shippingAddress->getCity();
    }

    /**
     * Get DeliveryState
     *
     * @return string
     */
    public function getDeliveryState()
    {
        /**
         * OrderAddressInterface
         *
         * @var \Magento\Sales\Api\Data\OrderAddressInterface $shippingAddress
         */
        $shippingAddress = $this->getOrderShippingAddress();
        return $shippingAddress->getRegion();
    }

    /**
     * Get DeliveryPostCode
     *
     * @return string
     */
    public function getDeliveryPostCode()
    {
        /**
         * OrderAddressInterface
         *
         * @var \Magento\Sales\Api\Data\OrderAddressInterface $shippingAddress
         */
        $shippingAddress = $this->getOrderShippingAddress();
        return $shippingAddress->getPostcode();
    }

    /**
     * Get DeliveryCountry
     *
     * @return string
     */
    public function getDeliveryCountry()
    {
        /**
         * OrderAddressInterface
         *
         * @var \Magento\Sales\Api\Data\OrderAddressInterface $shippingAddress
         */
        $shippingAddress = $this->getOrderShippingAddress();
        return $shippingAddress->getCountryId();
    }

    /**
     * Get DeliveryPhoneNumber
     *
     * @return string
     */
    public function getDeliveryPhoneNumber()
    {
        /**
         * OrderAddressInterface
         *
         * @var \Magento\Sales\Api\Data\OrderAddressInterface $shippingAddress
         */
        $shippingAddress = $this->getOrderShippingAddress();
        return $shippingAddress->getTelephone();
    }

    /**
     * GetDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return "Ebs payment method";
    }

    /**
     * Get Currency
     *
     * @return null|string
     */
    public function getCurrency()
    {
        return $this->_orderForm->getOrder()->getOrderCurrencyCode();
    }

    /**
     * Get INRCurrency
     *
     * @return string
     */
    public function getInrCurrency()
    {
        return "INR";
    }

    /**
     * Get SecureHash
     *
     * @return string
     */
    public function getSecureHash()
    {
        $hash = $this->_orderForm->getSecreteKey() . "|" .
            $this->_orderForm->getAccountId() . "|" .
            $this->_orderForm->getBillingStreet() . "|" .
            $this->_orderForm->getAmount() . "|" .
            $this->_orderForm->getChannel() . "|" .
            $this->_orderForm->getBillingCity() . "|" .
            $this->_orderForm->getBillingCountryCode() . "|" .
            $this->getInrCurrency() . "|" .
            $this->getDescription() . "|" .
            $this->_orderForm->getBillingEmail() . "|" .
            $this->_orderForm->getMode() . "|" .
            $this->_orderForm->getCustomerFullName() . "|" .
            $this->_orderForm->getBillingPhoneNumber() . "|" .
            $this->_orderForm->getBillingPostalCode() . "|" .
            $this->_orderForm->getReferenceNumber() . "|" .
            $this->_orderForm->getReturnUrl() . "|" .
            $this->getDeliveryAddress() . "|" .
            $this->getDeliveryCity() . "|" .
            $this->getDeliveryCountry() . "|" .
            $this->getDeliveryName() . "|" .
            $this->getDeliveryPhoneNumber() . "|" .
            $this->getDeliveryPostCode() . "|" .
            $this->getDeliveryState() . "|" .
            $this->_orderForm->getBillingRegion();
        return strtoupper(md5($hash));

    }
}