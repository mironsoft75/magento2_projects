<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\ResourceModel;

class Tags extends AbstractClass
{

    public function _construct()
    {
        $this->_init('amasty_blog_tags', 'tag_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getId()){
            $name = str_replace("/", " ", $object->getName());
            $slug = $this->urlHelper->generateSlug($name);
            $object->setUrlKey($slug);
        }
        return parent::_beforeSave($object);
    }

}
