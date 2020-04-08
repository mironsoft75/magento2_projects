<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Wishlist\Model\Cache;

use Magento\Framework\Cache\Frontend\Decorator\TagScope;
use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Store\Model\StoreManagerInterface;

class Wishlist extends TagScope
{
    const TYPE_IDENTIFIER = 'codilar_wishlist_cache';
    const CACHE_TAG = 'CODILAR_WISHLIST_CACHE';
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Wishlist constructor.
     * @param FrontendPool $cacheFrontendPool
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        FrontendPool $cacheFrontendPool,
        StoreManagerInterface $storeManager
    )
    {
        parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
        $this->storeManager = $storeManager;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCacheIdentifier() {
        $store = $this->storeManager->getStore()->getCode();
        return self::TYPE_IDENTIFIER."_".$store."_wishlist";
    }
}
