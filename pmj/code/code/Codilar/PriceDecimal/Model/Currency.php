<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 06/5/19
 * Time: 4:24 PM
 */

namespace Codilar\PriceDecimal\Model;

use Magento\Framework\CurrencyInterface;
use Magento\Framework\Currency as MagentoCurrency;
use Codilar\PriceDecimal\Model\ConfigInterface;

/** @method getPricePrecision */
class Currency extends MagentoCurrency implements CurrencyInterface
{

    use PricePrecisionConfigTrait;

    /**
     * @var \Codilar\PriceDecimal\Model\ConfigInterface
     */
    public $moduleConfig;

    /**
     * Currency constructor.
     *
     * @param \Magento\Framework\App\CacheInterface      $appCache
     * @param \Codilar\PriceDecimal\Model\ConfigInterface $moduleConfig
     * @param null                                       $options
     * @param null                                       $locale
     */
    public function __construct(
        \Magento\Framework\App\CacheInterface $appCache,
        ConfigInterface $moduleConfig,
        $options = null,
        $locale = null
    ) {
        $this->moduleConfig = $moduleConfig;
        parent::__construct($appCache, $options, $locale);
    }
}
