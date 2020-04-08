<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Helper;

class Posts extends \Magento\Framework\App\Helper\AbstractHelper
{

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    const STATUS_HIDDEN = 2;
    const STATUS_SCHEDULED = 3;

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

    /**
     * Posts constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Magento\Framework\App\Helper\Context $context
     */
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