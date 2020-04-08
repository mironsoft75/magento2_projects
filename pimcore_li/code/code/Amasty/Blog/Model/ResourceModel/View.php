<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\ResourceModel;

class View extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null  
    ) {
        parent::__construct($context, $connectionName);
    }

    public function _construct()
    {    
        $this->_init('amasty_blog_views', 'view_id');
    }

    public function loadByPostAndSession($object, $postId, $sessionId)
    {
        $select = $this->getConnection()->select()
            ->from(
                ['views' => $this->getTable('amasty_blog_views')]
            )
            ->where('views.post_id = :post_id')
            ->where('views.session_id = :session_id');

        $views = $this->getConnection()->fetchAll($select, [':post_id' => $postId, ':session_id' => $sessionId]);

        foreach ($views as $view){
            $object->addData($view);
            return $this;
        }

        return $this;
    }

    public function deleteRowsBefore($date)
    {
        $write = $this->getConnection();
        $write->beginTransaction();
        $write->delete($this->getMainTable(), "created_at <= '{$date}'");
        $write->commit();
        return $this;
    }

}