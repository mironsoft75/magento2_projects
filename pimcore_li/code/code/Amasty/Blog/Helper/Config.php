<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CONFIG_REGISTRY = 'amblog_config';
    
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
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Amasty\Blog\Model\Blog\Config
     */
    private $blogConfig;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\Registry $registry,
        \Amasty\Blog\Model\Blog\Config $blogConfig,
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->backendUrl = $backendUrl;
        $this->registry = $registry;
        $this->blogConfig = $blogConfig;
    }

    public function getConfig()
    {
        if (!$this->registry->registry(self::CONFIG_REGISTRY)) {
            $config = $this->blogConfig->get();
            $this->registry->register(self::CONFIG_REGISTRY, $config);
        }
        
        return$this->registry->registry(self::CONFIG_REGISTRY);
    }

    public function getArrayFromPath($path)
    {
        $config = $this->getConfig();
        if (isset($config[$path])) {
            return $config[$path];
        } else {
            return [];
        }
    }

}