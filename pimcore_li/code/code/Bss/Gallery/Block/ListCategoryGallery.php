<?php
/** 
 * BSS Commerce Co. 
 * 
 * NOTICE OF LICENSE 
 * 
 * This source file is subject to the EULA 
 * that is bundled with this package in the file LICENSE.txt. 
 * It is also available through the world-wide-web at this URL: 
 * http://bsscommerce.com/Bss-Commerce-License.txt 
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE 
 * ================================================================= 
 * This package designed for Magento COMMUNITY edition 
 * BSS Commerce does not guarantee correct work of this extension 
 * on any other Magento edition except Magento COMMUNITY edition. 
 * BSS Commerce does not provide extension support in case of 
 * incorrect edition usage. 
 * ================================================================= 
 * 
 * @category   BSS 
 * @package    Bss_Gallery 
 * @author     Extension Team 
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com ) 
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt 
 */ 
namespace Bss\Gallery\Block;

use Bss\Gallery\Api\Data\CategoryInterface;
use Bss\Gallery\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Framework\App\Filesystem\DirectoryList;

class ListCategoryGallery extends \Magento\Framework\View\Element\Template implements
    \Magento\Framework\DataObject\IdentityInterface
{

    protected $subDir = 'Bss/Gallery/Category';
    protected $subDirItem = 'Bss/Gallery/Item';
    protected $_storeManager;
    protected $_urlInterface;
    protected $_dataHelper;

    /**
     * @var \Bss\Gallery\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Bss\Gallery\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory ,
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Bss\Gallery\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Bss\Gallery\Model\ResourceModel\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Bss\Gallery\Helper\Category $dataHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_itemCollectionFactory = $itemCollectionFactory;
        $this->fileSystem = $context->getFilesystem();
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_imageFactory = $imageFactory;
        $this->_dataHelper = $dataHelper;
        $categoryCollection = $this->getCategories();
        $this->setCollection($categoryCollection);
        $this->_storeManager = $context->getStoreManager();
        $this->_urlInterface = $context->getUrlBuilder();
    }

    public function getCategories()
    {
        // Check if categories has already been defined
        // makes our block nice and re-usable! We could
        // pass the 'categories' data to this block, with a collection
        // that has been filtered differently!
        if (!$this->hasData('categories')) {
            $categories = $this->_categoryCollectionFactory
                ->create()
                ->addFilter('is_active', 1)
                ->addOrder(
                    CategoryInterface::CREATE_TIME,
                    CategoryCollection::SORT_ORDER_ASC
                );
            $this->setData('categories', $categories);
        }
        return $this->getData('categories');
    }

    public function getFirstCategoryItems()
    {
        $category = $this->getCollection()->getFirstItem();
        $item_ids = explode(',', $category->getData('Item_ids'));
        $limit = $this->getItemPerPage();
        if ($item_ids != '' && $item_ids != null) {
            $itemCollection = $this->_itemCollectionFactory->create();
            $itemCollection->addFieldToSelect('*')->addFieldToFilter('item_id', array('in' => $item_ids))->addFieldToFilter('is_active', array('eq' => 1));
            $itemCollection->setOrder('sorting', 'ASC');
            $itemCollection->getSelect()->limit($limit);
            return $itemCollection;
        }
    }

    public function getFirstCategoryItemsId()
    {
        $this->getCollection()->getFirstItem()->getId();
    }

    public function countItems($category)
    {
        $item_ids = $category->getData('Item_ids');
        if ($item_ids != null) {
            return sizeof(explode(',', $item_ids));
        }
        return '0';

    }

    public function countFirstCategoryItems()
    {
        $category = $this->getCollection()->getFirstItem();
        $item_ids = $category->getData('Item_ids');
        if ($item_ids != null) {
            return sizeof(explode(',', $item_ids));
        }
        return '0';
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Bss\Gallery\Model\Category::CACHE_TAG . '_' . 'list'];
    }

    public function getThumbnailUrl($thumbnailName)
    {
        if ($thumbnailName && @getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image' . $thumbnailName))) {
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image' . $thumbnailName;
        }
        return $this->getViewFileUrl('Bss_Gallery::images/default-image.jpg');
    }

    public function getImageResize($image)
    {
        if (@getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image' . $image))) {
            if (@!getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image/resized' . $image))) {
                $absPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($this->subDirItem . '/image/') . $image;
                $imageResized = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($this->subDirItem . '/image/resized') . $image;
                $imageResize = $this->_imageFactory->create();
                $imageResize->open($absPath);
                $imageResize->constrainOnly(TRUE);
                $imageResize->keepTransparency(TRUE);
                $imageResize->keepFrame(FALSE);
                $imageResize->keepAspectRatio(true);
                $imageResize->resize(350);
                $dest = $imageResized;
                $imageResize->save($dest);
                $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image/resized' . $image;
                return $resizedURL;
            } else {
                return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image/resized' . $image;
            }
        } else {
            return $this->getViewFileUrl('Bss_Gallery::images/default-image.jpg');
        }
    }

    public function getImageResizeItem($image)
    {
        if (@getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image' . $image))) {
            if (@!getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image/resized' . $image))) {
                $absPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($this->subDirItem . '/image/') . $image;
                $imageResized = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($this->subDirItem . '/image/resized') . $image;
                $imageResize = $this->_imageFactory->create();
                $imageResize->open($absPath);
                $imageResize->constrainOnly(TRUE);
                $imageResize->keepTransparency(TRUE);
                $imageResize->keepFrame(FALSE);
                $imageResize->keepAspectRatio(true);
                $imageResize->resize(350);
                $dest = $imageResized;
                $imageResize->save($dest);
                $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image/resized' . $image;
                return $resizedURL;
            } else {
                return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image/resized' . $image;
            }
        } else {
            return $this->getViewFileUrl('Bss_Gallery::images/default-image.jpg');
        }
    }

    public function getItemImageUrl($imageName)
    {
        if ($imageName && @getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image' . $imageName))) {
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDirItem . '/image' . $imageName;
        }
        return $this->getViewFileUrl('Bss_Gallery::images/default-image.jpg');
    }

    public function getLayoutType()
    {

        return $this->_dataHelper->getLayoutType();
    }

    public function getItemPerPage()
    {

        return $this->_dataHelper->getItemPerPage();
    }

    public function isAutoLoad()
    {

        return $this->_dataHelper->isAutoLoad();
    }

    public function getPageSpeed()
    {

        return $this->_dataHelper->getPageSpeed();
    }

    public function getTitlePosition()
    {

        return $this->_dataHelper->getTitlePosition();
    }

    public function getTransitionEffect()
    {

        return $this->_dataHelper->getTransitionEffect();
    }

    public function getBaseUrl()
    {
        return $this->_urlInterface->getBaseUrl();
    }

}
