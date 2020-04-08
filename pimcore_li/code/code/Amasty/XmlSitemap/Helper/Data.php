<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_XmlSitemap
 */


namespace Amasty\XmlSitemap\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const MEDIA_PATH = 'pub/media/';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     */
    public function getFrequency()
    {
        return [
            'always' => __('always'),
            'hourly' => __('hourly'),
            'daily' => __('daily'),
            'weekly' => __('weekly'),
            'monthly' => __('monthly'),
            'yearly' => __('yearly'),
            'never' => __('never'),
        ];
    }

    /**
     * @return array
     */
    public function getDateFormats()
    {
        return [
            'Y-m-d\TH:i:sP' => __('With time'),
            'Y-m-d' => __('Without time'),
        ];
    }

    /**
     * Check if url has media folder - get media folder by correct url
     * @param string $path
     * @param string|int $storeId
     * @return string
     */
    public function getCorrectUrl($path, $storeId)
    {
        $storeId = (int)$storeId;
        if (!$storeId) {
            $storeId = null;
        }

        if (strpos($path, self::MEDIA_PATH) !== false) {
            $url = $this->getStoreUrl($storeId, true);
            $url .= str_replace(self::MEDIA_PATH, '', $path);
        } else {
            $url = $this->getStoreUrl($storeId)
                . $path;
        }

        return $url;
    }

    private function getStoreUrl($storeId, $isMedia = false)
    {
        $store = $this->storeManager->getStore($storeId);
        if ($isMedia) {
            $url = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        } else {
            $url = $store->getBaseUrl();
        }

        if ($store->isUseStoreInUrl()) {
            $url = str_replace('/' . $store->getCode() . '/', '/', $url);
        }

        return $url;
    }

    /**
     * @param $path
     * @param int $storeId
     * @return mixed
     */
    public function getSettingsConfig($path, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
