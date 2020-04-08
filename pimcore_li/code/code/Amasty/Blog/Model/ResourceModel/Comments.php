<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\ResourceModel;

class Comments extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function _construct()
    {
        $this->_init('amasty_blog_comments', 'comment_id');
    }

}
