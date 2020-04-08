<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Helper;

class Status extends \Magento\Framework\App\Helper\AbstractHelper
{

    const STATUS_PENDING = 0;

    const STATUS_APPROVED = 1;

    const STATUS_REJECTED = 2;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var int
     */
    protected $_statusId = null;
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $backendUrl;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\App\Helper\Context $context
    ){
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->backendUrl = $backendUrl;
    }
}