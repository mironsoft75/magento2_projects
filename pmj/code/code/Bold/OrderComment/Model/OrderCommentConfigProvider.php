<?php

namespace Bold\OrderComment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class OrderCommentConfigProvider implements ConfigProviderInterface
{
    const CONFIG_MAX_LENGTH = 'sales/ordercomments/max_length';

    const CONFIG_MAX_ORDER_TOTAL = 'sales/ordercomments/order_total_amount';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfig()
    {
        return [
            'max_length' => (int) $this->scopeConfig->getValue(self::CONFIG_MAX_LENGTH),
            'order_total_amount' => (int) $this->scopeConfig->getValue(self::CONFIG_MAX_ORDER_TOTAL)
        ];
    }

}
