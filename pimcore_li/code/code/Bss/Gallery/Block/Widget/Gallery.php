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
namespace Bss\Gallery\Block\Widget;

use Magento\Framework\App\Filesystem\DirectoryList;

class Gallery extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = 'widget/gallery.phtml';
    protected $subDir = 'Bss/Gallery/Item';
    protected $_dataHelper;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Bss\Gallery\Model\Category $category,
        \Bss\Gallery\Model\CategoryFactory $categoryFactory,
        \Bss\Gallery\Model\ResourceModel\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Bss\Gallery\Helper\Category $dataHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_itemCollectionFactory = $itemCollectionFactory;
        $this->_category = $category;
        $this->_categoryFactory = $categoryFactory;
        $this->urlBuilder = $context->getUrlBuilder();
        $this->fileSystem = $context->getFilesystem();
        $this->_imageFactory = $imageFactory;
        $this->_dataHelper = $dataHelper;

    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        return $this;
    }

    /**
     * @return \Bss\Galery\Model\Category
     */
    public function getCategory()
    {
        // Check if category has already been defined
        // makes our block nice and re-usable! We could
        // pass the 'category' data to this block, with a collection
        // that has been filtered differently!
        if (!$this->hasData('category')) {
            if ($this->getBssGalleryCategory()) {
                /** @var \Bss\Gallery\Model\Category $page */
                $category = $this->_category->load($this->getBssGalleryCategory());
            } else {
                $category = $this->_category;
            }
            $this->setData('category', $category);
        }
        return $this->getData('category');
    }

    public function getCollection()
    {
        $category = $this->getCategory();
        $item_ids = explode(',', $category->getData('Item_ids'));
        if ($item_ids != '') {
            $itemCollection = $this->_itemCollectionFactory->create();
            $itemCollection->addFieldToSelect('*')->addFieldToFilter('item_id', array('in' => $item_ids))->addFieldToFilter('is_active', array('eq' => 1));
            $itemCollection->setOrder('sorting', 'ASC');
            return $itemCollection;
        }
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Bss\Gallery\Model\Category::CACHE_TAG . '_' . $this->getCategory()->getId()];
    }

    public function getImageUrl($imageName)
    {
        if ($imageName && @getimagesize($this->getUrl('pub/media/Bss/Gallery/Item/') . 'image' . $imageName)) {
            return $this->getUrl('pub/media/Bss/Gallery/Item/') . 'image' . $imageName;
        }
        return $this->getViewFileUrl('Bss_Gallery::images/default-image.jpg');
    }

    public function getImageResize($image)
    {
        if (@getimagesize($this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDir . '/image' . $image)) {
            if (@!getimagesize($this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDir . '/image/resized' . $image)) {
                $absPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($this->subDir . '/image/') . $image;
                $imageResized = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($this->subDir . '/image/resized') . $image;
                $imageResize = $this->_imageFactory->create();
                $imageResize->open($absPath);
                $imageResize->constrainOnly(TRUE);
                $imageResize->keepTransparency(TRUE);
                $imageResize->keepFrame(FALSE);
                $imageResize->keepAspectRatio(true);
                $imageResize->resize(350);
                $dest = $imageResized;
                $imageResize->save($dest);
                $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDir . '/image/resized' . $image;
                return $resizedURL;
            } else {
                return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDir . '/image/resized' . $image;
            }
        } else {
            return $this->getViewFileUrl('Bss_Gallery::images/default-image.jpg');
        }
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

    /**
     * Get relevant path to template
     *
     * @return string
     */
    public function getTemplate()
    {
        if(!$this->_dataHelper->isEnabledInFrontend())
            return "";
        return $this->_template;
    }
}
