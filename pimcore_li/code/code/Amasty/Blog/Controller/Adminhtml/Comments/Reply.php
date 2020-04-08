<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Comments;

class Reply extends \Amasty\Blog\Controller\Adminhtml\Comments
{
    public function execute()
    {
        return $this->_redirect('*/*/edit', ['reply_to_id' => $this->getRequest()->getParam('comment_id')]);
    }
}
