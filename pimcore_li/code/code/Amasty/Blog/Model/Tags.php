<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model;

class Tags extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * cache tag
     */
    const CACHE_TAG = 'amblog_tag';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    public function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Blog\Model\ResourceModel\Tags');
    }
    
    public function loadTagProducts($tagId)
    {
        $this->getResourceCollection()->loadTagProducts($this, $tagId);
        return $this;
    }

    public function getTagsListByNames($tagsArray)
    {
        $tagsList = $this->getResourceCollection()->addFieldToFilter('name', ['in'=>$tagsArray])->load();
        return $tagsList;
    }

    public function getTagUrl($page = 1)
    {
        return $this
            ->urlHelper
            ->setStoreId($this->getStoreId())
            ->getUrl($this->getId(), \Amasty\Blog\Helper\Url::ROUTE_TAG, $page);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [
            \Amasty\Blog\Model\Lists::CACHE_TAG,
            self::CACHE_TAG . '_' . $this->getId()
        ];

        return $identities;
    }
}
