<?php

namespace MageArray\News\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Newscat
 * @package MageArray\News\Model
 */
class Newscat extends AbstractModel
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init('MageArray\News\Model\ResourceModel\Newscat');
    }

    /**
     * @param $urlKey
     * @return mixed
     */
    public function checkUrlKey($urlKey)
    {
        return $this->_getResource()->checkUrlKey($urlKey);
    }

    /**
     * @return $this
     */
    public function beforeSave()
    {
        $this->_cacheManager->clean();
        return $this;
    }
}
