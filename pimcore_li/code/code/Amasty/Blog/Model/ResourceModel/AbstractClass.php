<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\ResourceModel;

class AbstractClass extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Amasty\Blog\Helper\Url
     */
    protected $urlHelper;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Amasty\Blog\Helper\Url $urlHelper,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->urlHelper = $urlHelper;
    }

    public function _construct()
    {
        parent::_construct();
    }
}