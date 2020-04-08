<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Model\Payment;

use Codilar\Checkout\Model\Payment\Method\AbstractMethod;
use Codilar\CheckoutPaypal\Model\Config;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\UrlInterface;
use Magento\Payment\Model\Method\Logger;

class CheckoutPaypal extends AbstractMethod
{
    const CODE = "codilar_checkout_paypal";
    protected $_code = self::CODE;

    /**
     * @var Config
     */
    private $config;
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * CheckoutPaypal constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Logger $logger
     * @param UrlInterface $url
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     * @param DirectoryHelper|null $directory
     * @param Config $config
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
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        DirectoryHelper $directory = null
    )
    {
        $this->config = $config;
        $this->url = $url;
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $url, $resource, $resourceCollection, $data, $directory);
    }

    /**
     * @return boolean
     */
    public function getIsOnline()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
       return $this->url->getBaseUrl()."checkoutpaypal/payment/request";
    }

    /**
     * @return string
     */
    public function getInstructions()
    {
        return $this->config->getInstructions();
    }
}