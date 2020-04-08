<?php

namespace Codilar\Base\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader;
use Psr\Log\LoggerInterface;

/**
 * Class Data
 * @package Codilar\Base\Helper
 */
class Data extends AbstractHelper
{
    const XML_PATH_PRICE_BREAK_UP_ENABLED = 'product_price_breakup/settings/price_breakup';
    const XML_PATH_PRODUCT_IMAGE_MAX_WIDTH = 'product_price_breakup/settings/max_width';
    const XML_PATH_PRODUCT_IMAGE_MAX_HEIGHT = 'product_price_breakup/settings/max_height';

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Reader
     */
    private $moduleReader;
    /**
     * @var Csv
     */
    private $fileCsv;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $_customerSession;
    /**
     * @var \Magento\Wishlist\Model\Wishlist
     */
    private $_wishlistModel;
    private $compare;


    /**
     * Data constructor.
     * @param Context $context
     * @param LoggerInterface $logger
     * @param Reader $moduleReader
     * @param \Magento\Wishlist\Model\Wishlist $wishlistModel
     * @param \Magento\Customer\Model\Session $customerSession
     * @param Csv $fileCsv
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        Reader $moduleReader,
        \Magento\Wishlist\Model\Wishlist $wishlistModel,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Catalog\Helper\Product\Compare $compare,
        Csv $fileCsv
    )
    {
        parent::__construct($context);
        $this->logger = $logger;
        $this->moduleReader = $moduleReader;
        $this->fileCsv = $fileCsv;
        $this->_customerSession = $customerSession;
        $this->_wishlistModel = $wishlistModel;
        $this->compare = $compare;
    }

    /**
     * @param $fileName
     * @return array|string
     * @throws \Exception
     */
    public function readCsvFile($fileName)
    {
        $directory = $this->moduleReader->getModuleDir('',$this->_getModuleName());
        $file = $directory . '/Files/' . $fileName;
        $data = '';
        if (file_exists($file)) {
            $data = $this->fileCsv->getData($file);
        }
        return $data;
    }

    /**
     * @return \Magento\Wishlist\Model\ResourceModel\Item\Collection|null
     */
    public function getWishlistCollection(){
        $customerSession = $this->_customerSession->create();
        if($customerSession->isLoggedIn()){
            $customerId = $customerSession->getCustomerId();
            $collection = $this->_wishlistModel->loadByCustomerId($customerId,true)->getItemCollection();
            return $collection;
        }
        return null;
    }

    /**
     * @param $_productId
     * @return bool
     */
    public function inWishlist($_productId){
        $wishlistCollection = $this->getWishlistCollection();
        if($wishlistCollection)
        {
            foreach ( $wishlistCollection->getData() as $wishlistItem){
                if($_productId == $wishlistItem['product_id']){
                    return true;
                }
            }
        }

    }
    public function inComparelist($_productId){
        $compareCollection = $this->compare->getItemCollection();
        if($compareCollection)
        {
            foreach ( $compareCollection->getData() as $compareItem){
                if($_productId == $compareItem['product_id']){
                    return true;
                }
            }
        }
    }

    /**
    * @return bool
    */
    public function isProductPriceBreakUp()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_PRICE_BREAK_UP_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getImageMaxWidth()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PRODUCT_IMAGE_MAX_WIDTH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getImageMaxHeight()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PRODUCT_IMAGE_MAX_HEIGHT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

}