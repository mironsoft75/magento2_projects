<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Cache;

use Magento\Framework\Cache\Frontend\Decorator\TagScope;

class ProductAttributes extends TagScope
{
    /**
     * Cache type code unique among all cache types
     */
    const TYPE_IDENTIFIER = 'codilar_product_attributes';

    /**
     * Cache tag used to distinguish the cache type from all other cache
     */
    const CACHE_TAG = 'CODILAR_PRODUCT_ATTRIBUTES';
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $context;

    /**
     * @param \Magento\Framework\App\Cache\Type\FrontendPool $cacheFrontendPool
     * @param \Magento\Framework\App\Http\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Cache\Type\FrontendPool $cacheFrontendPool,
        \Magento\Framework\App\Http\Context $context
    )
    {
        parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
        $this->context = $context;
    }
}