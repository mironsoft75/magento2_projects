<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\Config\Source;
use Magento\Framework\Option\ArrayInterface;

class Colors implements ArrayInterface
{

    public function toOptionArray()
    {
        return [
            ['value'=>'amblog-classic', 'label'=> __('Classic')],
            ['value'=>'amblog-red', 'label'=> __('Red')],
            ['value'=>'amblog-green', 'label'=> __('Green')],
            ['value'=>'amblog-blue', 'label'=> __('Blue')],
            ['value'=>'amblog-grey', 'label'=> __('Grey')],
            ['value'=>'amblog-old-magento', 'label'=> __('Old Magento')],
        ];
    }
}

