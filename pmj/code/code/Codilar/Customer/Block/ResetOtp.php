<?php

namespace Codilar\Customer\Block;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ResetOtp
 * @package Codilar\Customer\Block
 */
class ResetOtp extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Registry
     */
    protected $_registry;
    /**
     * @var
     */
    protected $_mobileNumber;
    /**
     * @var
     */
    protected $_customerId;
    /**
     * @var FormKey
     */
    protected $_formKey;
    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * ResetOtp constructor.
     * @param Registry                    $registry
     * @param Context                     $context
     * @param FormKey                     $formKey
     * @param CustomerFactory             $customerFactory
     * @param StoreManagerInterface       $storeManager
     * @param CustomerRepositoryInterface $customerRepository
     * @param array                       $data
     */
    public function __construct(
        Registry $registry,
        Context $context,
        FormKey $formKey,
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_formKey = $formKey;
        $this->_customerFactory = $customerFactory;
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return mixed
     */
    public function getMobileNumber()
    {
        $mobileNumber = $this->_registry->registry("mobile_number");
        $this->_mobileNumber = $mobileNumber;
        $this->_registry->unregister("mobile_number");
        return $mobileNumber;
    }


    /**
     * @return int
     */
    public function getCustomerId()
    {
        $this->_customerId = $this->_registry->registry("customer_id");
        $this->_registry->unregister("customer_id");
        return $this->_customerId;
    }

    /**
     * @return string
     */
    public function getResetMobileAction()
    {
        $mobileNumber = $this->_mobileNumber;
        $id = $this->_customerId;
        $baseUrl = $this->getUrl("customer/account/resetpasswordmobile/?id=$id&mobilenumber=$mobileNumber");
        return $baseUrl;
    }

    /**
     * @return string
     */
    public function getFormKey()
    {
        return $this->_formKey->getFormKey();
    }
}
