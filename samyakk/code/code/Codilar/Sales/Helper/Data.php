<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/4/19
 * Time: 7:11 PM
 */

namespace Codilar\Sales\Helper;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Shipping\Model\Config;
use \Magento\Payment\Model\Config as PaymentConfig;

class Data extends AbstractHelper
{
    /**
     * @var Config
     */
    private $shipConfig;

    protected $scopeConfig;
    /**
     * @var PaymentConfig
     */
    private $paymentConfig;

    /**
     * Data constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $shipConfig
     * @param PaymentConfig $paymentConfig
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Config $shipConfig,
        PaymentConfig $paymentConfig
    )
    {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->shipConfig = $shipConfig;
        $this->paymentConfig = $paymentConfig;
    }

    /**
     * @return array
     */
    public function getShippingMethods()
    {

        $activeCarriers = $this->shipConfig->getAllCarriers();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $methods = [];
        foreach($activeCarriers as $carrierCode => $carrierModel) {
            if ($carrierMethods = $carrierModel->getAllowedMethods()) {
                foreach ($carrierMethods as $methodCode) {
                    $code = $carrierCode.'_'.$methodCode;
                    $carrierTitle = $this->scopeConfig->getValue('carriers/'.$carrierCode.'/title', $storeScope);
                    $methods[] = [
                        'value' => $code,
                        'label' => $carrierTitle
                    ];
                }

            }
        }
        return $methods;

    }

    /**
     * @return array
     */
    public function getPaymentMethods()
    {
        $payments = $this->paymentConfig->getActiveMethods();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $methods = [];
        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = $this->scopeConfig
                ->getValue('payment/'.$paymentCode.'/title', $storeScope);
            $methods[] = array(
                'label' => $paymentTitle,
                'value' => $paymentCode
            );
        }
        return $methods;
    }

    /**
     * @param string $code
     * @param string $type
     * @return string
     */
    public function getMethodLabel($code, $type = "shipping")
    {
        if ($type == "shipping") {
            $methods = $this->getShippingMethods();
        } else {
            $methods = $this->getPaymentMethods();
        }
        foreach ($methods as $method) {
            if ($code == $method['value']) {
                return $method['label'];
            }
        }
    }
}