<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\Blog;
use Amasty\Blog\Model\Blog\Config\Reader;
use Magento\Framework\Config\CacheInterface;
use Magento\Framework\Config\Data;

class Config extends Data
{

    public function __construct(
        Reader $reader,
        CacheInterface $cache
    ) {
        parent::__construct($reader, $cache, 'amblog_blog');
    }
}
