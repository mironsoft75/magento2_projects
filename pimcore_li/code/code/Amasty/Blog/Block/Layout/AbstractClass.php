<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Layout;

class AbstractClass extends \Magento\Framework\View\Element\Template
{
    
    public function getHead()
    {
        return $this->getLayout()->getBlock('head');
    }
    
    public function getExtraHead()
    {
        return $this->getLayout()->getBlock('extra_head');
    }

    public function wantAsserts()
    {
        $license = $this->getLayout()->getBlock('layout');
        if ($license){

            $alias = $this->getNameInLayout();
            $parts = explode(".", $alias);
            $alias = $parts[count($parts) - 1];
            return $license->isBlockUsed($alias);
        }
        return true;
    }
}