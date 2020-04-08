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
namespace Bss\Gallery\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class Ajax extends Action
{

    protected $context;
    protected $_category;
    protected $itemModel;
    protected $_dataHelper;
    protected $_fileSystem;
    protected $_storeManager;
    protected $subDir = 'Bss/Gallery/Item';
    protected $urlBuilder;
    protected $_imageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Bss\Gallery\Model\Category $category,
        \Bss\Gallery\Model\Item $itemModel,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Bss\Gallery\Helper\Category $dataHelper,
        Filesystem $fileSystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->context = $context;
        parent::__construct($context);
        $this->_category = $category;
        $this->itemModel = $itemModel;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_dataHelper = $dataHelper;
        $this->urlBuilder = $context->getUrl();
        $this->_fileSystem = $fileSystem;
        $this->_imageFactory = $imageFactory;
        $this->_storeManager = $storeManager;
    }

    public function execute()
    {
        $itemIds = $this->getRequest()->getPost('itemIds');
        $cateId = $this->getRequest()->getPost('cateId');
        $result;
        if ($cateId) {
            $limit = $this->_dataHelper->getItemPerPage();
            $category = $this->_category->load($cateId);
            if ($category) {
                $cateItemIds = explode(',', $category->getData('Item_ids'));
                $items = $this->itemModel->getCollection()
                    ->addFieldToFilter('item_id', array('in' => $cateItemIds));
                if($itemIds) {
                    $items->addFieldToFilter('item_id', array('nin' => $itemIds));
                }
                $items->setOrder('sorting', 'ASC')->setPageSize($limit);
                if ($items->getSize() > 0) {
                    $html = '';
                    foreach ($items as $item) {
                        $html .= '<li class="gallery-category-list-item" item-id="' . $item->getId() . '">';
                        $html .= '<div class="gallery-category-item">';
                        if ($item->getVideo() && $item->getVideo() != '') {
                            $html .= '<a title="' . $item->getDescription() . '" href="' . $item->getVideo() . '" class="fancybox fancybox.iframe" rel="gallery-' . $cateId . '">';
                            $html .= '<img src="' . $this->getImageResize($item->getImage()) . '" />';
                            $html .= '</a>';
                        } else {
                            $html .= '<a title="' . $item->getDescription() . '" href="' . $this->getImageUrl($item->getImage()) . '" class="fancybox" rel="gallery-' . $cateId . '">';
                            $html .= '<img src="' . $this->getImageResize($item->getImage()) . '" />';
                            $html .= '</a>';
                        }
                        $html .= '</div>';
                        $html .= '<h4 class="gallery-category-item-title">';
                        $html .= $item->getTitle();
                        $html .= '</h4>';
                        $html .= '</li>';
                    }
                    $result = $html;
                }else if(!$itemIds) {
                    $result = '<p>'.__('This Album has no image !') .'</p>';
                }

            }
        }

        return $this->resultJsonFactory->create()->setData($result);
    }

    public function getImageUrl($imageName)
    {
        if ($imageName && @getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'Bss/Gallery/Item' . '/image' . $imageName))) {
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'Bss/Gallery/Item' . '/image' . $imageName;
        }
        return $this->getViewFileUrl('Bss_Gallery::images/default-image.jpg');
    }

    public function getImageResize($image)
    {
        if (@getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDir . '/image' . $image))) {
            if (@!getimagesize(str_replace('https://', 'http://', $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->subDir . '/image/resized' . $image))) {
                $absPath = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($this->subDir . '/image/') . $image;
                $imageResized = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($this->subDir . '/image/resized') . $image;
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
}
