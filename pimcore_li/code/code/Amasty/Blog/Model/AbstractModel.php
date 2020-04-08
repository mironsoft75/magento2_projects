<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model;

use Amasty\Blog\Helper\Image;
use Amasty\Blog\Helper\Settings;

class AbstractModel extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Amasty\Blog\Helper\Data
     */
    protected $dataHelper;
    /**
     * @var \Amasty\Blog\Helper\Date
     */
    protected $dateHelper;
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $localeResolver;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManagerInterface;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManagerInterface;
    /**
     * @var \Amasty\Blog\Helper\Url
     */
    protected $urlHelper;

    protected $_storeId = null;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteAddress;
    /**
     * @var Settings
     */
    protected $settings;
    /**
     * @var Image
     */
    protected $imageHelper;
    /**
     * @var \Amasty\Blog\Helper\Resize
     */
    protected $resizeHelper;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Amasty\Blog\Helper\Data $dataHelper,
        \Amasty\Blog\Helper\Date $dateHelper,
        \Amasty\Blog\Helper\Url $urlHelper,
        \Amasty\Blog\Helper\Settings $settings,
        \Amasty\Blog\Helper\Image $imageHelper,
        \Amasty\Blog\Helper\Resize $resizeHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Magento\Framework\ObjectManagerInterface $objectManagerInterface,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dataHelper = $dataHelper;
        $this->dateHelper = $dateHelper;
        $this->localeResolver = $localeResolver;
        $this->objectManagerInterface = $objectManagerInterface;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->urlHelper = $urlHelper;
        $this->registry = $registry;
        $this->customerSession = $customerSession;
        $this->remoteAddress = $remoteAddress;
        $this->settings = $settings;
        $this->imageHelper = $imageHelper;
        $this->resizeHelper = $resizeHelper;
    }
    
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        $this->setData('store_id', $storeId);
        return $this;
    }

    public function getStoreId()
    {
        return $this->hasData('store_id') ? $this->getData('store_id') : $this->_storeId;
    }
    
}