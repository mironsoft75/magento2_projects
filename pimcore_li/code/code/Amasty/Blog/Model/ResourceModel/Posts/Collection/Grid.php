<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\ResourceModel\Posts\Collection;

class Grid extends \Amasty\Blog\Model\ResourceModel\Posts\Collection
{

    /**
     * Initialize db select
     *
     * @return $this
     */
    protected function _initSelect()
    {
        $this->addStores();
        parent::_initSelect();
        return $this;
    }

}
