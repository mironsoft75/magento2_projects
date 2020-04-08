<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbySeo
 */


namespace Amasty\ShopbySeo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class UrlParser extends AbstractHelper
{
    /**
     * @var  Data
     */
    protected $seoHelper;

    protected $aliasDelimiter;

    public function __construct(
        Context $context,
        Data $seoHelper
    ) {
        parent::__construct($context);
        $this->seoHelper = $seoHelper;
        $this->aliasDelimiter = $context->getScopeConfig()->getValue('amasty_shopby_seo/url/option_separator');
    }

    public function parseSeoPart($seoPart)
    {
        $seoPart = str_replace('/', $this->aliasDelimiter, $seoPart);
        $aliases = explode($this->aliasDelimiter, $seoPart);
        $params = $this->parseAliases($aliases);

        return $params;
    }

    /**
     * @param array $aliases
     * @return array|false
     */
    protected function parseAliases($aliases)
    {
        $attributeOptionsData = $this->seoHelper->getOptionsSeoData();
        $params = [];
        foreach ($aliases as $currentAlias) {
            if (in_array($currentAlias, array_keys($attributeOptionsData))) {
                continue;
            }
            foreach ($attributeOptionsData as $attributeCode => $optionsData) {
                foreach ($optionsData as $optionId => $alias) {
                    if ($alias === $currentAlias) {
                        $params = $this->addParsedOptionToParams($optionId, $attributeCode, $params);
                    }
                }
            }
        }

        return $params ?: false;
    }

    protected function addParsedOptionToParams($value, $paramName, $params)
    {
        if (array_key_exists($paramName, $params)) {
            $params[$paramName] .= ',' . $value;
        } else {
            $params[$paramName] = '' . $value;
        }

        return $params;
    }
}
