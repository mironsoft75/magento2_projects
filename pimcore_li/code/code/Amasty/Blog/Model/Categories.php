<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model;

class Categories extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    /**
     * cache tag
     */
    const CACHE_TAG = 'amblog_category';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    public function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Blog\Model\ResourceModel\Categories');
    }

    /**
     * Get category URL
     *
     * @param array $params
     * @param int $page
     * @return string
     */
    public function getUrl($params = [], $page = 1)
    {
        return $this->urlHelper->setStoreId($this->storeManagerInterface->getStore()->getId())
            ->getUrl($this->getId(), \Amasty\Blog\Helper\Url::ROUTE_CATEGORY, $page);
    }

    public function activate()
    {
        $this->setStatus(self::STATUS_ENABLED);
        $this->save();
        return $this;
    }

    public function inactivate()
    {
        $this->setStatus(self::STATUS_DISABLED);
        $this->save();
        return $this;
    }

    public function getStores()
    {
        if (!$this->hasData('stores')) {
            $stores = $this->_getResource()->getStores($this->getId());
            $storesArray = [];
            foreach ($stores as $store) {
                $storesArray[] = $store['store_id'];
            }
            $this->setData('stores', $storesArray);
        }

        return $this->_getData('stores');
    }

    public function beforeSave()
    {
        if (!$this->urlHelper->validate($this->getUrlKey())) {
            $this->setUrlKey($this->urlHelper->prepare($this->getUrlKey()));
        }

        return parent::beforeSave();
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
