<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Cms\Helper;

use Codilar\Cms\Model\Cache\Type;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Cache extends AbstractHelper
{
    /**
     * @var Type
     */
    private $cacheType;

    /**
     * Cache constructor.
     * @param Context $context
     * @param Type $cacheType
     */
    public function __construct(
        Context $context,
        Type $cacheType
    )
    {
        parent::__construct($context);
        $this->cacheType = $cacheType;
    }

    /**
     * @return Type
     */
    public function getCacheData()
    {
        $cacheType = $this->cacheType;
        $cacheType->load(Type::TYPE_IDENTIFIER);
        return $cacheType;
    }
}
