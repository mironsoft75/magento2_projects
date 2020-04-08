<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Content\Post;

class Wrapper extends \Magento\Framework\View\Element\Template
{

    public function useGoogleProfile()
    {
        return !!$this->getPost()->getPostedBy() && !!$this->getPost()->getGoogleProfile();
    }

    public function getGoogleProfileUrl()
    {
        return $this->getPost()->getGoogleProfile();
    }

}