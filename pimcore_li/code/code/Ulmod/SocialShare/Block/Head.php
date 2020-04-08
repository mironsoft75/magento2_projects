<?php
/**
 * SocialShare
 *
 * @package     Ulmod_SocialShare
 * @author      Ulmod <support@ulmod.com>
 * @copyright   Copyright (c) 2016 Ulmod (http://www.ulmod.com/)
 * @license     http://www.ulmod.com/license-agreement.html
 */

namespace Ulmod\SocialShare\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Ulmod\SocialShare\Helper\Data as HelperData;
use Magento\Framework\ObjectManagerInterface;

class Head extends Template
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectFactory;

    /**
     * @param   Context $context,
     * @param   HelperData $helperData,
     * @param   ObjectManagerInterface $objectManager,
     * @param   StoreManagerInterface $storeManager,
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->helperData    = $helperData;
        $this->objectManager = $objectManager;
        parent::__construct($context, $data);
    }
    
    /**
     * Is the module enabled in configuration.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->helperData->isEnabled();
    }
    
    /**
     * Check if current page is home page
     *
     * @return bool
     */
    public function getIsHomePage()
    {
        return $this->helperData->isHomePage();
    }
    
    /**
     * Check if current page is cms page
     *
     * @return bool
     */
    public function getIsCmsPage()
    {
        return $this->helperData->isCmsPage();
    }

    /**
     * Check if current page is category page
     *
     * @return bool
     */
    public function getIsCategoryPage()
    {
        return $this->helperData->isCategoryPage();
    }
    
    /**
     * Check if current page is product page
     *
     * @return bool
     */
    public function getIsProductPage()
    {
        return $this->helperData->isProductPage();
    }

    /**
     * Check if current page is additional page
     *
     * @return bool
     */
    public function getIsAdditionalPage()
    {
        return $this->helperData->isAdditionalPage();
    }
}
