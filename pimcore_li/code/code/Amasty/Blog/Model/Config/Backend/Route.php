<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\Config\Backend;

class Route extends \Magento\Framework\App\Config\Value
{
    /** @var  \Amasty\Blog\Helper\Url */
    private $urlHelper;

    protected function _construct()
    {
        $this->urlHelper = $this->getData('urlHelper');
        parent::_construct();
    }

    public function beforeSave()
    {
        if (!$this->urlHelper->validate($this->getValue())) {
            $this->setValue($this->urlHelper->prepare($this->getValue()));
        }

        return parent::beforeSave();
    }
}
