<?php

namespace Codilar\ViewCache\Block\Adminhtml\Cache;

class Additional extends \Magento\Backend\Block\Template
{
    /**
     *return String url
     */
    public function getCleanViewCache()
    {
        return $this->getUrl('view/*/viewCache');
    }
}
