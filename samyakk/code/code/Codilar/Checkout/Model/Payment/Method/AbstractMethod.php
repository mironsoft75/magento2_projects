<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model\Payment\Method;

use Codilar\Checkout\Api\Data\PaymentMethodOptionInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\UrlInterface;
use Magento\Payment\Model\Method\Logger;
use Magento\Payment\Model\Method\AbstractMethod as Subject;

abstract class AbstractMethod extends Subject implements PaymentMethodOptionInterface
{
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * AbstractMethod constructor.
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
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        DirectoryHelper $directory = null
    )
    {
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $resource, $resourceCollection, $data, $directory);
        $this->url = $url;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        return $this;
    }

    /**
     * @param boolean $isOnline
     * @return $this
     */
    public function setIsOnline($isOnline)
    {
        return $this;
    }

    /**
     * @param string $redirectUrl
     * @return $this
     */
    public function setRedirectUrl($redirectUrl)
    {
        return $this;
    }

    /**
     * @param string $instructions
     * @return $this
     */
    public function setInstructions($instructions)
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCsrfToken($csrfToken)
    {
        return $this;
    }

    public function setTitle($title)
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCsrfToken()
    {
        return null;
    }

    /**
     * @param string|null $routePath
     * @param string|null $routeParams
     * @return string
     */
    public function getUrl($routePath = null, $routeParams = null)
    {
        return $this->url->getUrl($routePath, $routeParams);
    }

    public function isOffline()
    {
        return !$this->getIsOnline();
    }
}