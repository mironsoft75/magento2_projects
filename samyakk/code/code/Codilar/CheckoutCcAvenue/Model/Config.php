<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutCcAvenue\Model;

use Codilar\Checkout\Model\Source\Mode;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{

    const XML_GROUP = "ccavenue_payment_gateway";
    const XML_SECTION = "payment";

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var array
     */
    private $data;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Config constructor.
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
        $this->scopeConfig = $scopeConfig;
        $this->data = $data;
        $this->storeManager = $storeManager;
    }

    /**
     * @return bool
     */
    public function getIsActive() {
        return (bool)$this->getValue("active", self::XML_GROUP, self::XML_SECTION, ScopeInterface::SCOPE_STORE, false);
    }

    /**
     * @return string
     */
    public function getMerchantId() {
        return $this->getValue("merchant_id");
    }

    /**
     * @return string
     */
    public function getGatewayId() {
        return $this->getValue("gateway_id");
    }

    /**
     * @return string
     */
    public function getSecretKey() {
        return $this->getValue("secret_key");
    }

    /**
     * @return string
     */
    public function getMode() {
        return $this->getValue("mode");
    }


    /**
     * @return string
     */
    public function getTitle() {
        return $this->getValue("title");
    }

    /**
     * @return string
     */
    public function getInstructions() {
        return $this->getValue("instructions");
    }

    /**
     * @return string
     */
    public function getOrderPrefix() {
        return $this->getValue("order_prefix");
    }

    /**
     * @return string
     */
    public function getRedirectMessage() {
        return $this->getValue("redirect_message");
    }



    /**
     * @return string
     */
    public function getPendingOrderStatus() {
        return $this->getValue("pending_order_status");
    }

    /**
     * @return string
     */
    public function getSuccessOrderStatus() {
        return $this->getValue("success_order_status");
    }


    /**
     * @return string
     */
    public function getSuccessOrderMessage() {
        return $this->getValue("success_order_message");
    }

    /**
     * @return string
     */
    public function getFailureOrderStatus() {
        return $this->getValue("failed_order_status");
    }

    /**
     * @return string
     */
    public function getFailureOrderMessage() {
        return $this->getValue("failure_order_message");
    }

    /**
     * @return string
     */
    public function getTestPaymentUrl() {
        return $this->getValue("test_payment_url");
    }

    /**
     * @return string
     */
    public function getLivePaymentUrl() {
        return $this->getValue("live_payment_url");
    }

    /**
     * @return string
     */
    public function getPaymentUrl() {
        if ($this->getMode() === Mode::MODE_LIVE) {
            return $this->getLivePaymentUrl();
        } else {
            return $this->getTestPaymentUrl();
        }
    }

    /**
     * @return string
     */
    public function getResponseReturnUrl() {
        return $this->getValue("response_return_url");
    }

    /**
     * @return false|string
     */
    public function getLogoSrc() {
        $useLogo = $this->getValue("use_logo");
        if ($useLogo) {
            try {
                $logoSrc = "ccavenue/config/logo/".$this->getValue("logo");
                return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).$logoSrc;
            } catch (NoSuchEntityException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @return false|string
     */
    public function getAutoOrderCancelTime() {
        if ($this->getValue("auto_order_cancel")) {
            return $this->getValue("auto_order_cancel_time");
        }
        return false;
    }

    /**
     * @param string $field
     * @param string $group
     * @param string $section
     * @param string $scope
     * @param bool $validateIsActive
     * @return string
     */
    public function getValue($field, $group = self::XML_GROUP, $section = self::XML_SECTION, $scope = ScopeInterface::SCOPE_STORE, $validateIsActive = true) {
        $path = $section . '/' . $group . '/' . $field;
        if (!array_key_exists($path.$scope, $this->data)) {
            $this->data[$path.$scope] = $validateIsActive && !$this->getIsActive() ? false : $this->scopeConfig->getValue($path, $scope);
        }
        return $this->data[$path.$scope];
    }
}