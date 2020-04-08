<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\ResourceModel\View;

class Collection extends \Amasty\Blog\Model\ResourceModel\Abstracts\Collection
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Blog\Model\View', 'Amasty\Blog\Model\ResourceModel\View');
    }
}