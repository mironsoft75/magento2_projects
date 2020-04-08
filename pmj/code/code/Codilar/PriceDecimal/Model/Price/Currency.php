<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 18/6/19
 * Time: 6:12 PM
 */

namespace Codilar\PriceDecimal\Model\Price;


use Magento\Directory\Model\CurrencyConfig;
use Codilar\PriceDecimal\Helper\Data;

class Currency extends \Magento\Directory\Model\Currency
{

    /**
     * @var Data
     */
    private $helperData;

   public function __construct(\Magento\Framework\Model\Context $context, Data $helperData,\Magento\Framework\Registry $registry, \Magento\Framework\Locale\FormatInterface $localeFormat, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Directory\Helper\Data $directoryHelper, \Magento\Directory\Model\Currency\FilterFactory $currencyFilterFactory, \Magento\Framework\Locale\CurrencyInterface $localeCurrency, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = [], CurrencyConfig $currencyConfig = null)
   {
       parent::__construct($context, $registry, $localeFormat, $storeManager, $directoryHelper, $currencyFilterFactory, $localeCurrency, $resource, $resourceCollection, $data, $currencyConfig);
       $this->helperData = $helperData;
   }

    /**
     * @param float $price
     * @param array $options
     * @return string
     */
    public function formatTxt($price, $options = [])
    {
        if (!is_numeric($price)) {
            $price = $this->_localeFormat->getNumber($price);
        }

        if($this->helperData->getInrEnable()) {
            $price = sprintf("%F", $price);
            $actualPrice = $this->inrPriceFormat($price);
            return $actualPrice;
        }
        /**
         * Fix problem with 12 000 000, 1 200 000
         *
         * %f - the argument is treated as a float, and presented as a floating-point number (locale aware).
         * %F - the argument is treated as a float, and presented as a floating-point number (non-locale aware).
         */
        $price = sprintf("%F", $price);
        $price =$this->_localeCurrency->getCurrency($this->getCode())->toCurrency($price, $options);
        return $price;
    }


    /**
     * @param $price
     * @return float|string
     */
    public function inrPriceFormat($price)
    {
        $price = floor($price);
        $expoUnit = "" ;
        if(strlen($price)>3) {
            $lastThree = substr($price, strlen($price)-3, strlen($price));
            $restUnits = substr($price, 0, strlen($price)-3); // extracts the last three digits
            $restUnits = (strlen($restUnits)%2 == 1)?"0".$restUnits:$restUnits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expUnit = str_split($restUnits, 2);
            for($i=0; $i<sizeof($expUnit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if($i==0) {
                    $expoUnit .= (int)$expUnit[$i].","; // if is first value , convert into integer
                } else {
                    $expoUnit .= $expUnit[$i].",";
                }
            }
            $amount = $expoUnit.$lastThree;
        } else {
            $amount = $price;
        }
        return $this->getCurrencySymbol().$amount; // price without currency symbol is the currency symbol.
    }
}