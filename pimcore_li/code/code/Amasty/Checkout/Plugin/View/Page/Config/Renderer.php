<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin\View\Page\Config;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\View\Asset\File;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Config\Renderer as MagentoRenderer;

class Renderer
{
    const CACHE_KEY = 'amasty_checkout_should_load_css_file';

    protected $filesToCheck = ['css/styles-l.css', 'css/styles-m.css'];

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(
        Config $config,
        CacheInterface $cache
    ) {
        $this->config = $config;
        $this->cache = $cache;
    }

    /**
     * Add our css file if less functionality is missing
     *
     * @param MagentoRenderer $subject
     * @param array $resultGroups
     *
     * @return array
     */
    public function beforeRenderAssets(MagentoRenderer $subject, $resultGroups = [])
    {
        $shouldLoad = $this->cache->load(self::CACHE_KEY);

        if ($shouldLoad === false) {
            $shouldLoad = $this->isShouldLoadCss();
            $this->cache->save($shouldLoad, self::CACHE_KEY);
        }

        if ($shouldLoad) {
            $this->config->addPageAsset('Amasty_Checkout::css/source/mkcss/am-checkout.css');
        }

        return [$resultGroups];
    }

    /**
     * @return int
     */
    private function isShouldLoadCss()
    {
        /** @var \Magento\Framework\View\Asset\GroupedCollection $collection */
        $collection = $this->config->getAssetCollection();
        $found = 0;
        /** @var File $item */
        foreach ($collection->getAll() as $item) {
            if ($item instanceof File
                && in_array($item->getFilePath(), $this->filesToCheck)
            ) {
                $found++;
            }
        }

        return (int)($found < count($this->filesToCheck));
    }
}
