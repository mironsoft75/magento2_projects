<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Content;

class Grid extends Lists
{
    const CACHE_PREFIX = 'amblog_grid_';
    const PAGER_BLOCK_NAME = 'amblock_grid_pager';


    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        return $this;
    }
}