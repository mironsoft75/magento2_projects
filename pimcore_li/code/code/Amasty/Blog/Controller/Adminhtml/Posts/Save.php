<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Adminhtml\Posts;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\DriverInterface;

class Save extends \Amasty\Blog\Controller\Adminhtml\Posts
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Io\File $ioFile,
        \Amasty\Blog\Helper\Url $urlHelper,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        parent::__construct(
            $context,
            $coreRegistry,
            $resultForwardFactory,
            $resultPageFactory,
            $filesystem,
            $ioFile,
            $urlHelper,
            $fileUploaderFactory
        );
        $this->date = $dateTime;
    }

    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->_objectManager->create('Amasty\Blog\Model\Posts');
                $data = $this->getRequest()->getPostValue();
                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $data
                );
                $data = $inputFilter->getUnescaped();
                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }

                $model->addData($data);

                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());

                $this->_prepareForSave($model);

                $currentTimestamp = $this->date->gmtTimestamp();
                $publishedDate = strtotime($model->getPublishedAt());
                if ($model->getStatus() == \Amasty\Blog\Model\Posts::STATUS_SCHEDULED
                    || $model->getStatus() == \Amasty\Blog\Model\Posts::STATUS_ENABLED) {
                    if ($publishedDate > $currentTimestamp) {
                        $model->setStatus(\Amasty\Blog\Model\Posts::STATUS_SCHEDULED);
                    } else {
                        $model->setStatus(\Amasty\Blog\Model\Posts::STATUS_ENABLED);
                    }
                }

                if (!$model->getPublishedAt()) {
                    $model->setPublishedAt(date('Y-m-d H:i:s', $currentTimestamp));
                }

                $model->save();
                $this->messageManager->addSuccess(__('You saved the item.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('*/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('*/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    protected function _prepareForSave($model)
    {
        //upload images
        $data = $this->getRequest()->getPost();
        $path = $this->_filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            'amasty/blog/'
        );

        $this->_ioFile->checkAndCreateFolder($path);

        $imagesTypes = array('post', 'list');
        foreach ($imagesTypes as $type) {
            $field = $type . '_thumbnail';

            $files = $this->getRequest()->getFiles();
            $isRemove = array_key_exists('remove_' . $field, $data);
            $hasNew   = !empty($files[$field]['name']);

            try {
                // remove the old file
                if ($isRemove) {
                    $oldName = isset($data['old_' . $field]) ? $data['old_' . $field] : '';
                    if ($oldName) {
                        $this->_ioFile->rm($path . $oldName);
                        $model->setData($field, '');
                    }
                }

                // upload a new if any
                if (!$isRemove && $hasNew) {
                    //find the first available name
                    $newName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $files[$field]['name']);

                    /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => $field]);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $result = $uploader->save($path, $newName);
                    $fileName = '';
                    if ($result['error'] ==0) {
                        $fileName = $result['file'];
                    }
                    $model->setData($field, $fileName);
                }
            } catch (\Exception $e) {
                if ($e->getCode() != \Magento\MediaStorage\Model\File\Uploader::TMP_NAME_EMPTY) {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        }

        if (!$model->getUrlKey()) {
            $model->setUrlKey($this->urlHelper->generate($model->getTitle()));
        }

        return true;
    }
}