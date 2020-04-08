<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\ResourceModel\Comments\Collection;

class Grid extends \Amasty\Blog\Model\ResourceModel\Comments\Collection
{

    /**
     * Initialize db select
     *
     * @return $this
     */
    protected function _initSelect()
    {
        $this->addPosts();
        parent::_initSelect();
        return $this;
    }

}
