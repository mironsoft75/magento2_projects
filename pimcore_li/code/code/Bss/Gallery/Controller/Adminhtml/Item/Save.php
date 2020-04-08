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
namespace Bss\Gallery\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;


class Save extends \Magento\Backend\App\Action
{

    /**
     * @param Action\Context $context
     */
    protected $uploaderFactory;
    protected $imageModel;

    public function __construct(
        Action\Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Bss\Gallery\Model\Item\Image $imageModel
        ){
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->imageModel = $imageModel;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Bss_Gallery::item_save');
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
            /** @var \Bss\Gallery\Model\Item $model */
            $model = $this->_objectManager->create('Bss\Gallery\Model\Item');

            $id = $this->getRequest()->getParam('item_id');
            if ($id) {
                $model->load($id);
            }
            if(is_array($this->getRequest()->getParam('category_ids'))){
                $category_ids = implode(',',$this->getRequest()->getParam('category_ids'));
                $data['category_ids'] = $category_ids;
            }else{
                $category_ids = '';
                $data['category_ids'] = $category_ids;
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'gallery_item_prepare_save',
                ['item' => $model, 'request' => $this->getRequest()]
                );

            try {
                $imageName = $this->uploadFileAndGetName('image', $this->imageModel->getBaseDir(), $data);
                $model->setImage($imageName);
                $sort = intval($data['sorting']);
                if($sort != ''){
                    $model->setData('sorting',$sort);
                }else{
                    $model->setData('sorting',10);
                }
                $model->save();
                //save items id in category
                $cateIds = explode(',',$model->getCategoryIds());
                $id = $model->getId();
                $allCategories = $this->_objectManager->create('Bss\Gallery\Model\ResourceModel\Category\CollectionFactory')->create();
                foreach ($allCategories as $cate) {
                    $_catId = $cate->getId();
                    $itemIds = explode(',',$cate->getData('Item_ids'));
                    if(in_array($_catId,$cateIds)){
                        if(!in_array($id,$itemIds)){
                            array_push($itemIds, $id);
                            $itemIds = ltrim(rtrim(implode(',',$itemIds),","),",");
                            $cate->setData('Item_ids',$itemIds);
                            $cate->save();
                        }
                    }else{
                        if(in_array($id,$itemIds)){
                            $key = array_search($id, $itemIds);
                            unset($itemIds[$key]);
                            $cate->setData('Item_ids',implode(',',$itemIds));
                            $cate->save();
                        }
                    }
                }


                $this->messageManager->addSuccess(__('You saved this Item.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['item_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the item.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['item_id' => $this->getRequest()->getParam('item_id')]);
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
                 throw new \Exception(__($e->getMessage()));
            } else {
                if (isset($data[$input]['value'])) {
                    return $data[$input]['value'];
                }
            }
        }
        return '';
    }
}

