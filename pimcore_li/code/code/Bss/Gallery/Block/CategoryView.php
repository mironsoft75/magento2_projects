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

use Magento\Framework\App\Filesystem\DirectoryList;

class CategoryView extends \Magento\Framework\View\Element\Template implements
    \Magento\Framework\DataObject\IdentityInterface
{

    protected $subDir = 'Bss/Gallery/Item';
    protected $_dataHelper;
    protected $_coreRegistry;
    // protected $_objectManager;
    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Bss\Galery\Model\Category $category
     * @param \Bss\Galery\Model\CategoryFactory $categoryFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Bss\Gallery\Model\Category $category,
        \Bss\Gallery\Model\CategoryFactory $categoryFactory,
        \Bss\Gallery\Model\ResourceModel\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Bss\Gallery\Helper\Category $dataHelper,
        \Magento\Framework\Registry $registry,
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
        $this->_coreRegistry = $registry;

        $category = $this->getCategory();
        $item_ids = explode(',', $category->getData('Item_ids'));
        if ($item_ids != '') {
            $itemCollection = $this->_itemCollectionFactory->create();
            $itemCollection->addFieldToSelect('*')->addFieldToFilter('item_id', array('in' => $item_ids))->addFieldToFilter('is_active', array('eq' => 1));
            $itemCollection->setOrder('sorting', 'ASC');
            $this->setCollection($itemCollection);
        }
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        /** @var \Magento\Theme\Block\Html\Pager */
        if ($this->getItemLayoutType() == 'standard') {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'gallery.items.list.pager'
            );
            $itemPerPage = $this->_dataHelper->getItemPerPage();
            if ($itemPerPage) {
                $pager->setLimit($itemPerPage)
                    ->setCollection($this->getCollection());
            } else {
                $pager->setLimit(20)
                    ->setCollection($this->getCollection());
            }
            $this->setChild('pager', $pager);
        }
        $this->getCollection()->load();

        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return \Bss\Galery\Model\Category
     */
    public function getCategory()
    {
        return $this->_coreRegistry->registry('category');
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
        if ($imageName && @getimagesize(str_replace('https://', 'http://', $this->getUrl('pub/media/Bss/Gallery/Item/') . 'image' . $imageName))) {
            return $this->getUrl('pub/media/Bss/Gallery/Item/') . 'image' . $imageName;
        }
        return $this->getViewFileUrl('Bss_Gallery::images/default-image.jpg');
    }

    public function getImageResize($image)
    {
        if (@getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDir . '/image' . $image))) {
            if (@!getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDir . '/image/resized' . $image))) {
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

    // public function getItemLayoutType()
    // {

    //     return $this->_dataHelper->getItemLayoutType();
    // }
}
