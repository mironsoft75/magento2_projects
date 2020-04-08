<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbySeo
 */


namespace Amasty\ShopbySeo\Helper;

use Amasty\ShopbyBase\Api\Data\FilterSettingInterface;
use Amasty\ShopbyBase\Model\Cache\Type;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option;
use Magento\Framework\App\Cache;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManager;
use Amasty\ShopbyBase\Model\ResourceModel\FilterSetting\CollectionFactory;
use Amasty\Shopby\Helper\Group;
use Amasty\ShopbyBase\Model\ResourceModel\OptionSetting\CollectionFactory as OptionSettingCollectionFactory;
use Amasty\ShopbyBase\Model\Integration\IntegrationFactory;

class Data extends AbstractHelper
{
    const CANONICAL_ROOT = 'amasty_shopby_seo/canonical/root';
    const CANONICAL_CATEGORY = 'amasty_shopby_seo/canonical/category';
    const AMASTY_SHOPBY_SEO_URL_SPECIAL_CHAR = 'amasty_shopby_seo/url/special_char';
    const AMASTY_SHOPBY_SEO_URL_ATTRIBUTE_NAME = 'amasty_shopby_seo/url/attribute_name';
    const AMASTY_SHOPBY_SEO_URL_FILTER_WORD = 'amasty_shopby_seo/url/filter_word';
    const AMSHOPBY_ROOT_GENERAL_URL = 'amshopby_root/general/url';

    /**
     * @var CollectionFactory
     */
    private $settingCollectionFactory;

    /**
     * @var Option\CollectionFactory
     */
    private $optionCollectionFactory;

    /**
     * @var  OptionSettingCollectionFactory
     */
    private $optionSettingCollectionFactory;

    /**
     * @var  StoreManager
     */
    private $storeManager;

    /**
     * @var  \Magento\Catalog\Model\Product\Url
     */
    private $productUrl;

    /**
     * @var  Type
     */
    private $cache;

    /**
     * @var Cache\StateInterface
     */
    private $cacheState;

    /**
     * @var Group
     */
    private $groupHelper;

    /**
     * @var array|null
     */
    private $seoSignificantAttributeCodes;

    /**
     * @var array|null
     */
    private $optionsSeoData;

    /**
     * @var IntegrationFactory
     */
    private $integrationFactory;

    /**
     * @var Url
     */
    private $urlHelper;

    public function __construct(
        Context $context,
        CollectionFactory $settingCollectionFactory,
        Option\CollectionFactory $optionCollectionFactory,
        \Magento\Catalog\Model\Product\Url $productUrl,
        OptionSettingCollectionFactory $optionSettingCollectionFactory,
        StoreManager $storeManager,
        Cache $cache,
        Group $groupHelper,
        IntegrationFactory $integrationFactory,
        \Amasty\ShopbySeo\Helper\Url\Proxy $urlHelper,
        Cache\StateInterface $cacheState
    ) {
        parent::__construct($context);
        $this->settingCollectionFactory = $settingCollectionFactory;
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->optionSettingCollectionFactory = $optionSettingCollectionFactory;
        $this->storeManager = $storeManager;
        $this->productUrl = $productUrl;
        $this->cache = $cache;
        $this->cacheState = $cacheState;
        $this->groupHelper = $groupHelper;
        $this->integrationFactory = $integrationFactory;
        $this->urlHelper = $urlHelper;
    }

    public function getOptionsSeoData()
    {
        $cache_id = 'amshopby_seo_options_data' . $this->storeManager->getStore()->getId();
        if ($this->optionsSeoData === null && $this->cacheState->isEnabled(Type::TYPE_IDENTIFIER)) {
            $cached = $this->cache->load($cache_id);
            if ($cached !== false) {
                $this->optionsSeoData = unserialize($cached);
            }
        }
        if ($this->optionsSeoData === null) {
            $this->optionsSeoData = [];
            $aliasHash = [];

            $hardcodedAliases = $this->loadHardcodedAliases();
            foreach ($hardcodedAliases as $row) {
                $alias = $this->buildUniqueAlias($row['url_alias'], $aliasHash);
                if (strpos($row['filter_code'], 'attr_') === 0) {
                    $attributeCode = substr($row['filter_code'], strlen('attr_'));
                } else {
                    $attributeCode = '';
                }

                $this->optionsSeoData[$attributeCode][$row['value']] = $alias;
                $aliasHash[$alias] = $row['value'];
            }
            $dynamicAliases = $this->loadDynamicAliasesExcluding(array_values($aliasHash));
            $ids = [];
            foreach ($dynamicAliases as $row) {
                $attributeCode = isset($row['attribute_code']) ? $row['attribute_code'] : '';
                if (!array_key_exists($row['attribute_id'], $ids)) {
                    $ids[$row['attribute_id']] = $attributeCode;
                }

                $alias = $this->buildUniqueAlias($row['value'], $aliasHash);
                $optionId = $row['option_id'];
                $this->optionsSeoData[$row['attribute_code']][$optionId] = $alias;
                $aliasHash[$alias] = $optionId;
            }
            foreach ($ids as $id => $code) {
                $data = $this->groupHelper->getAliasGroup($id);
                if ($data) {
                    foreach ($data as $key => $record) {
                        $alias = $this->buildUniqueAlias($record, $aliasHash);
                        $this->optionsSeoData[$code][$key] = $alias;
                        $aliasHash[$record] = $key;
                    }
                }
            }
            if ($this->cacheState->isEnabled(Type::TYPE_IDENTIFIER)) {
                $this->cache->save(serialize($this->optionsSeoData), $cache_id, [Type::CACHE_TAG]);
            }
        }

        return $this->optionsSeoData;
    }

    /**
     * @return array
     */
    private function loadHardcodedAliases()
    {
        $aliases = [];
        if ($this->urlHelper->isSeoUrlEnabled()) {
            $storeId = $this->storeManager->getStore()->getId();
            $aliases = $this->optionSettingCollectionFactory->create()->getHardcodedAliases($storeId);
        }

        return $aliases;
    }

    /**
     * @param array $excludeOptionIds
     * @return array
     */
    private function loadDynamicAliasesExcluding($excludeOptionIds = [])
    {
        $seoAttributeCodes = $this->getSeoSignificantAttributeCodes();

        $collection = $this->optionCollectionFactory->create();
        $collection->join(['a' => 'eav_attribute'], 'a.attribute_id = main_table.attribute_id', ['attribute_code']);
        $collection->addFieldToFilter('attribute_code', ['in' => $seoAttributeCodes]);
        $collection->setStoreFilter();
        $select = $collection->getSelect();
        if ($excludeOptionIds) {
            $select->where('`main_table`.`option_id` NOT IN (' . join(',', $excludeOptionIds) . ')');
        }
        $statement = $select->query();
        $rows = $statement->fetchAll();
        return $rows;
    }

    /**
     * @return array
     */
    public function getSeoSignificantAttributeCodes()
    {
        if ($this->seoSignificantAttributeCodes === null) {
            $filterCodes = [];

            if ($this->urlHelper->isSeoUrlEnabled()) {
                $collection = $this->settingCollectionFactory->create();
                $collection->addFieldToFilter(FilterSettingInterface::IS_SEO_SIGNIFICANT, 1);
                $filterCodes = $collection->getColumnValues(FilterSettingInterface::FILTER_CODE);
                array_walk($filterCodes, function (&$code) {
                    if (substr($code, 0, 5) == \Amasty\ShopbyBase\Helper\FilterSetting::ATTR_PREFIX) {
                        $code = substr($code, 5);
                    }
                });
            }

            $brandCode = $this->getBrandAttributeCode();
            if (!in_array($brandCode, $filterCodes)) {
                $filterCodes[] = $brandCode;
            }

            $this->seoSignificantAttributeCodes = $filterCodes;
        }

        return $this->seoSignificantAttributeCodes;
    }

    private function buildUniqueAlias($value, $hash)
    {
        if (preg_match('@^[\d\.]+$@s', $value)) {
            $format = $value;
        } else {
            $format = $this->productUrl->formatUrlKey($value);
        }
        if ($format == '') {
            // Magento formats '-' as ''
            $format = '-';
        }

        $format = str_replace('-', $this->getSpecialChar(), $format);

        $unique = $format;
        for ($i=1; array_key_exists($unique, $hash); $i++) {
            $unique = $format . '-' . $i;
        }
        return $unique;
    }

    /**
     * @return string
     */
    public function getSpecialChar()
    {
        return $this->scopeConfig->getValue(self::AMASTY_SHOPBY_SEO_URL_SPECIAL_CHAR, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getOptionSeparator()
    {
        return $this->scopeConfig->getValue('amasty_shopby_seo/url/option_separator', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getCanonicalRoot()
    {
        return $this->scopeConfig->getValue(self::CANONICAL_ROOT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getCanonicalCategory()
    {
        return $this->scopeConfig->getValue(self::CANONICAL_CATEGORY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getGeneralUrl()
    {
        return $this->scopeConfig->getValue(self::AMSHOPBY_ROOT_GENERAL_URL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isIncludeAttributeName()
    {
        return $this->scopeConfig->getValue(self::AMASTY_SHOPBY_SEO_URL_ATTRIBUTE_NAME, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getFilterWord()
    {
        return $this->scopeConfig->getValue(self::AMASTY_SHOPBY_SEO_URL_FILTER_WORD, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getBrandAttributeCode()
    {
        /** @var \Amasty\ShopbyBrand\Helper\Data|\Amasty\ShopbyBase\Model\Integration\DummyObject $brandHelper */
        $brandHelper = $this->integrationFactory->get(\Amasty\ShopbyBrand\Helper\Data::class, true);
        return (string)$brandHelper->getBrandAttributeCode();
    }

    /**
     * @return string
     */
    public function getBrandUrlKey()
    {
        /** @var \Amasty\ShopbyBrand\Helper\Data|\Amasty\ShopbyBase\Model\Integration\DummyObject $brandHelper */
        $brandHelper = $this->integrationFactory->get(\Amasty\ShopbyBrand\Helper\Data::class, true);

        return (string)$brandHelper->getBrandUrlKey();
    }

    /**
     * @return \Magento\Framework\UrlInterface
     */
    public function getUrlBuilder()
    {
        return $this->_urlBuilder;
    }
}
