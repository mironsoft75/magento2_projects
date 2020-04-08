<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutBankTransfer\Model;


use Codilar\Checkout\Model\Payment\Method\AbstractMethod;
use Magento\Payment\Model\Method\Logger;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\UrlInterface;
use Codilar\Shipping\Model\Config as ShippingConfig;
use Magento\Backend\Model\Auth\Session as BackendSession;

class BankTransfer extends AbstractMethod
{
    const CODE = "bank_transfer";

    protected $_code = self::CODE;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var ShippingConfig
     */
    private $shippingConfig;
    /**
     * @var BackendSession
     */
    private $backendSession;

    /**
     * CcAvenue constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Logger $logger
     * @param UrlInterface $url
     * @param Config $config
     * @param ShippingConfig $shippingConfig
     * @param BackendSession $backendSession
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     * @param DirectoryHelper|null $directory
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Logger $logger,
        UrlInterface $url,
        Config $config,
        ShippingConfig $shippingConfig,
        BackendSession $backendSession,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        DirectoryHelper $directory = null
    )
    {
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $url, $resource, $resourceCollection, $data, $directory);
        $this->config = $config;
        $this->shippingConfig = $shippingConfig;
        $this->backendSession = $backendSession;
    }

    /**
     * @return boolean
     */
    public function getIsOnline()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->getUrl();
    }

    /**
     * @return string
     */
    public function getInstructions()
    {
        return $this->config->getInstructions();
    }

    /**
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        // Do not remove payment method for admin
        if ($this->backendSession->isLoggedIn()) {
            return true;
        }

        $zipcode = $quote->getShippingAddress()->getPostCode();
        $allowedZipcodes = array_column($this->shippingConfig->getAllowedZipcodes(), 'zipcode');
        if (in_array($zipcode, $allowedZipcodes)) {
            return true;
        } else {
            return false;
        }
    }
}