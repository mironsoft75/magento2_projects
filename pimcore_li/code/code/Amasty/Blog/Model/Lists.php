<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model;

class Lists extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * cache tag
     */
    const CACHE_TAG = 'amblog_list';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Blog URL
     *
     * @param array $params
     * @param int $page
     * @return string
     */
    public function getUrl($params = array(), $page = 1)
    {
        return $this->urlHelper->getUrl(null, null, $page);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG];
    }
}
