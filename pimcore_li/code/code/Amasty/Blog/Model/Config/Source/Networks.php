<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\Config\Source;
use Magento\Framework\Option\ArrayInterface;

class Networks implements ArrayInterface
{
    /**
     * @var \Amasty\Blog\Model\Networks
     */
    protected $networks;

    public function __construct(
        \Amasty\Blog\Model\Networks $networks
    ) {
        $this->networks = $networks;

    }

    public function toOptionArray()
    {
        return $this->networks->toOptionArray();
    }

}

