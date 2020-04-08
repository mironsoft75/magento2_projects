<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Shipping\Model;


use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;

class Carrier extends AbstractCarrier implements CarrierInterface
{

    const CODE = "samyakk";

    protected $_code = self::CODE;

    /**
     * @var ResultFactory
     */
    private $resultFactory;
    /**
     * @var MethodFactory
     */
    private $methodFactory;
    /**
     * @var Config
     */
    private $config;

    /**
     * Carrier constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param ResultFactory $resultFactory
     * @param MethodFactory $methodFactory
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        ResultFactory $resultFactory,
        MethodFactory $methodFactory,
        Config $config,
        array $data = []
    )
    {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->resultFactory = $resultFactory;
        $this->methodFactory = $methodFactory;
        $this->config = $config;
    }

    /**
     * Collect and get rates
     *
     * @param RateRequest $request
     * @return Result|boolean
     * @api
     */
    public function collectRates(RateRequest $request)
    {
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->resultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->methodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $amount = $this->getShippingPrice($request);

        if ($amount === false) {
            return false;
        }

        $method->setPrice($amount);
        $method->setCost($amount);

        $result->append($method);

        return $result;
    }

    /**
     * @param RateRequest $request
     * @return double|bool
     */
    protected function getShippingPrice(RateRequest $request)
    {
//        $allowedZipcodes = array_column($this->config->getAllowedZipcodes(), 'zipcode');
//        if (!in_array($request->getDestPostcode(), $allowedZipcodes)) {
//            return false;
//        }

        $chareableCategories = $this->config->getChargeableCategories();
        $items = $request->getAllItems();

        if (count($chareableCategories)) {
            $isChargeable = true;
            /** @var \Magento\Quote\Model\Quote\Item $item */
            foreach ($items as $item) {
                $productCategories = $item->getProduct()->getCategoryIds();
                foreach ($productCategories as $productCategory) {
                    if (!in_array((string)$productCategory, $chareableCategories)) {
                        $isChargeable = false;
                        break 2;
                    }
                }
            }
        } else {
            $isChargeable = false;
        }

        if ($isChargeable) {
            $slabs = $this->config->getCountryWiseSlabs();
            $country = $request->getDestCountryId();
            $cartTotal = $request->getPackageValue();
            foreach ($slabs as $slab) {
                if ($slab['country'] === '*' || $slab['country'] === $country) {
                    foreach ($slab['values'] as $value) {
                        $from = $value['from'];
                        $to = $value['to'];
                        if (
                            ($from === '*' || doubleval($from) <= $cartTotal) &&
                            ($to === '*' || doubleval($to) >= $cartTotal)
                        ) {
                            return doubleval($value['value']);
                        }
                    }
                }
            }
            return false;
        } else {
            return 0;
        }
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     * @api
     */
    public function getAllowedMethods()
    {
        return [$this->_code];
    }
}