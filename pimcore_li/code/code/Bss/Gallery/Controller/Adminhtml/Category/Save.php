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
namespace Bss\Gallery\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Save extends \Magento\Backend\App\Action
{

    /**
     * @param Action\Context $context
     */
    protected $uploaderFactory;
    protected $imageModel;
    protected $_jsHelper;

    public function __construct(
        Action\Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Bss\Gallery\Model\Category\Image $imageModel,
        \Magento\Backend\Helper\Js $jsHelper
        )
    {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->imageModel = $imageModel;
        $this->_jsHelper = $jsHelper;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Bss_Gallery::category_save');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \Bss\Gallery\Model\Category $model */
            $model = $this->_objectManager->create('Bss\Gallery\Model\Category');
            $itemIds = null;
            $id = $this->getRequest()->getParam('category_id');
            if ($id) {
                $model->load($id);
                $itemIds = $model->getData('Item_ids');
            }
            $category_image = $this->getRequest()->getPostValue('category_image');
            if (isset($category_image)) {
                $itemGridSerializedInputData = $this->_jsHelper->decodeGridSerializedInput($this->getRequest()->getPostValue('category_image'));
                $itemIds = [];
                foreach ($itemGridSerializedInputData as $key => $value) {
                    $itemIds[] = $key;
                }
                $itemIds = implode(',', $itemIds);
            }
            $model->setData($data);
            $model->setData('Item_ids', $itemIds);

            $this->_eventManager->dispatch(
                'gallery_category_prepare_save',
                ['category' => $model, 'request' => $this->getRequest()]
                );
            try {
                if($data['item_thumb_id']){
                    $category_thumb = $data['item_thumb_id'];
                    $itemModel = $this->_objectManager->create('Bss\Gallery\Model\Item');
                    $item = $itemModel->load($category_thumb);
                    $item_image = $item->getImage();
                    $model->setThumbnail($item_image);
                    $model->setItemThumbId($category_thumb);
                }else{
                    $model->setThumbnail('');
                    $model->setItemThumbId('');
                }

                $title = $data['title'];
                $metaKeywords = $data['category_meta_keywords'];
                $metaDescription = $data['category_meta_description'];
                $url = $this->formatUrl($title);
                
                if ($model->checkUrlKey($url) != null && $model->getId() != $model->checkUrlKey($url)) {
                    $url = $url . '-' . $model->getId();
                }
                $model->setCategoryMetaKeywords($metaKeywords);
                $model->setMetaDescription($metaDescription);
                $model->setUrlKey($url);
                $model->save();

                //set category_ids for item
                $category = $model;
                $id = $category->getId();
                $itemIds = explode(',',$category->getData('Item_ids'));
                $allItems = $this->_objectManager->create('Bss\Gallery\Model\ResourceModel\Item\CollectionFactory')->create();
                foreach ($allItems as $item) {
                    $_itemId = $item->getId();
                    $cateIds = explode(',',$item->getData('category_ids'));
                    if(in_array($_itemId,$itemIds)){
                        if(!in_array($id,$cateIds)){
                            array_push($cateIds, $id);
                            $cateIds = ltrim(rtrim(implode(',',$cateIds),","),",");
                            $item->setData('category_ids',$cateIds);
                            $item->save();
                        }
                    }else{
                        if(in_array($id,$cateIds)){
                            $key = array_search($id, $cateIds);
                            unset($cateIds[$key]);
                            $item->setData('category_ids',implode(',',$cateIds));
                            $item->save();
                        }
                    }
                }

                $this->messageManager->addSuccess(__('You saved this Album.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['category_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong, please try again !'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['category_id' => $this->getRequest()->getParam('category_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function uploadFileAndGetName($input, $destinationFolder, $data)
    {
        try {
            if (isset($data[$input]['delete'])) {
                return '';
            } else {
                $uploader = $this->uploaderFactory->create(['fileId' => $input]);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);
                $result = $uploader->save($destinationFolder);
                return $result['file'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
                // throw new FrameworkException($e->getMessage());
                // die($e->getMessage());
            } else {
                if (isset($data[$input]['value'])) {
                    return $data[$input]['value'];
                }
            }
        }
        return '';
    }

    public function formatUrl($str)
    {
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_| -]+/", '-', $clean);
        return $clean;
    }
}

