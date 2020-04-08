<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Cron;


class Scheduled
{
    /**
     * Website collection factory
     *
     * @var \Magento\Store\Model\ResourceModel\Website\CollectionFactory
     */
    protected $_websiteCollectionFactory;

    /**
     * Session factory
     *
     * @var \Magento\Persistent\Model\SessionFactory
     */
    protected $_sessionFactory;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    /**
     * @var \Amasty\Blog\Model\Posts
     */
    private $postsModel;

    public function __construct(
        \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websiteCollectionFactory,
        \Magento\Persistent\Model\SessionFactory $sessionFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Registry $registry,
        \Amasty\Blog\Model\Posts $postsModel,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_websiteCollectionFactory = $websiteCollectionFactory;
        $this->_sessionFactory = $sessionFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_registry     = $registry;
        $this->_objectManager = $objectManager;
        $this->postsModel = $postsModel;
    }

    /**
     * Clear expired persistent sessions
     *
     * @param \Magento\Cron\Model\Schedule $schedule
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Cron\Model\Schedule $schedule)
    {
        
        $posts = $this->postsModel->getCollection();
        $now = new \Zend_Date();

        $posts
            ->addFieldToFilter('status', \Amasty\Blog\Model\Posts::STATUS_SCHEDULED)
            ->addFieldToFilter('published_at', array('lt' => $now->toString(\Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT)))
        ;

        foreach ($posts as $post){
            $post->activateScheduled();
        }
    }


}
