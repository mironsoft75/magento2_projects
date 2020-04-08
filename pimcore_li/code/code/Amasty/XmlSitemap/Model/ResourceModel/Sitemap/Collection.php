<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_XmlSitemap
 */


namespace Amasty\XmlSitemap\Model\ResourceModel\Sitemap;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            'Amasty\XmlSitemap\Model\Sitemap',
            'Amasty\XmlSitemap\Model\ResourceModel\Sitemap'
        );
        $this->_idFieldName = 'id';
    }
}
