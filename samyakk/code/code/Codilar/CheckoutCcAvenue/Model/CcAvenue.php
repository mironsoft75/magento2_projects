<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutCcAvenue\Model;


use Codilar\Checkout\Model\Payment\Method\AbstractMethod;
use Magento\Payment\Model\Method\Logger;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\UrlInterface;

class CcAvenue extends AbstractMethod
{
    const CODE = "ccavenue_payment_gateway";

    const ORDER_NOT_VALID_EXCEPTION = "Order has been already processed or no order found. Please try again";

    protected $_code = self::CODE;
    /**
     * @var Config
     */
    private $config;

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
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        DirectoryHelper $directory = null
    )
    {
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $url, $resource, $resourceCollection, $data, $directory);
        $this->config = $config;
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
        return $this->getUrl('checkoutccavenue/request/index');
    }

    /**
     * @return string
     */
    public function getInstructions()
    {
        return $this->config->getInstructions();
    }
}