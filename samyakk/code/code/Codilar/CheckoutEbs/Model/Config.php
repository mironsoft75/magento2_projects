<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/9/19
 * Time: 12:08 PM
 */

namespace Codilar\CheckoutEbs\Model;

use Codilar\Checkout\Model\Source\Mode;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Config
 * @package Codilar\CheckoutEbs\Model
 */
class Config
{
    const XML_GROUP = "ebs_payment_gateway";
    const XML_SECTION = "payment";

    /**
     * ScopeConfigInterface
     *
     * @var ScopeConfigInterface
     */
    private $_scopeConfig;
    /**
     * Array
     *
     * @var array
     */
    private $_data;
    /**
     * StoreManagerInterface
     *
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_data = $data;
        $this->_storeManager = $storeManager;
    }

    /**
     * Is Active
     *
     * @return bool
     */
    public function getIsActive()
    {
        return (bool)$this->getValue
        (
            "active",
            self::XML_GROUP,
            self::XML_SECTION,
            ScopeInterface::SCOPE_STORE,
            false
        );
    }

    /**
     * Get AccountName
     *
     * @return string
     */
    public function getAccountName()
    {
        return $this->getValue("account_name");
    }

    /**
     * Get AccountId
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->getValue("account_id");
    }

    /**
     * Get SecretKey
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->getValue("secret_key");
    }

    /**
     * Get Mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->getValue("mode");
    }


    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getValue("title");
    }

    /**
     * Get Instructions
     *
     * @return string
     */
    public function getInstructions()
    {
        return $this->getValue("instructions");
    }

    /**
     * Get OrderPrefix
     *
     * @return string
     */
    public function getOrderPrefix()
    {
        return $this->getValue("order_prefix");
    }

    /**
     * Get Redirect Message
     *
     * @return string
     */
    public function getRedirectMessage()
    {
        return $this->getValue("redirect_message");
    }


    /**
     * Get Pending OrderStatus
     *
     * @return string
     */
    public function getPendingOrderStatus()
    {
        return $this->getValue("pending_order_status");
    }

    /**
     * Get SuccessOrderStatus
     *
     * @return string
     */
    public function getSuccessOrderStatus()
    {
        return $this->getValue("success_order_status");
    }


    /**
     * Get Success OrderMessage
     *
     * @return string
     */
    public function getSuccessOrderMessage()
    {
        return $this->getValue("success_order_message");
    }

    /**
     * Get FailureOrderStatus
     *
     * @return string
     */
    public function getFailureOrderStatus()
    {
        return $this->getValue("failed_order_status");
    }

    /**
     * Get FailureOderMessage
     *
     * @return string
     */
    public function getFailureOrderMessage()
    {
        return $this->getValue("failure_order_message");
    }

    /**
     * Get TestPaymentUrl
     *
     * @return string
     */
    public function getTestPaymentUrl()
    {
        return $this->getValue("test_payment_url");
    }

    /**
     * Get Channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->getValue("channel_for_standard_mode");

    }

    /**
     * Get LivePaymentUrl
     *
     * @return string
     */
    public function getLivePaymentUrl()
    {
        return $this->getValue("live_payment_url");
    }

    /**
     * Get PaymentUrl
     *
     * @return string
     */
    public function getPaymentUrl()
    {
        if ($this->getMode() === Mode::MODE_LIVE) {
            return $this->getLivePaymentUrl();
        } else {
            return $this->getTestPaymentUrl();
        }
    }

    /**
     * Get LiveReturnUrl
     *
     * @return string
     */
    public function getLiveReturnUrl()
    {
        return $this->getValue("live_return_url");

    }

    /**
     * Get TestReturnUrl
     *
     * @return string
     */
    public function getTestReturnUrl()
    {
        return $this->getValue("test_return_url");

    }

    /**
     * Get ReturnUrl
     *
     * @return string
     */
    public function getReturnUrl()
    {
        if ($this->getMode() === Mode::MODE_LIVE) {
            return $this->getLiveReturnUrl();
        } else {
            return $this->getTestReturnUrl();
        }
    }

    /**
     * Get ResponseReturnUrl
     *
     * @return string
     */
    public function getResponseReturnUrl()
    {
        return $this->getValue("response_return_url");
    }

    /**
     * Get LogoSrc
     *
     * @return false|string
     */
    public function getLogoSrc()
    {
        $useLogo = $this->getValue("use_logo");
        if ($useLogo) {
            try {
                $logoSrc = "ccavenue/config/logo/" . $this->getValue("logo");
                return $this->_storeManager->getStore()
                        ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $logoSrc;
            } catch (NoSuchEntityException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get AutoOrderCancelTime
     *
     * @return false|string
     */
    public function getAutoOrderCancelTime()
    {
        if ($this->getValue("auto_order_cancel")) {
            return $this->getValue("auto_order_cancel_time");
        }
        return false;
    }

    /**
     * Get Value
     *
     * @param string $field
     * @param string $group
     * @param string $section
     * @param string $scope
     * @param bool $validateIsActive
     * @return string
     */
    public function getValue(
        $field,
        $group = self::XML_GROUP,
        $section = self::XML_SECTION,
        $scope = ScopeInterface::SCOPE_STORE,
        $validateIsActive = true
    )
    {
        $path = $section . '/' . $group . '/' . $field;
        if (!array_key_exists($path . $scope, $this->_data)) {
            $this->_data[$path . $scope] = $validateIsActive &&
            !$this->getIsActive() ? false : $this->_scopeConfig
                ->getValue($path, $scope);
        }
        return $this->_data[$path . $scope];
    }
}