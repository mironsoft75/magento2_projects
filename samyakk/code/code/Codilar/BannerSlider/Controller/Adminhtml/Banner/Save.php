<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Controller\Adminhtml\Banner;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magestore\Bannerslider\Controller\Adminhtml\Banner;

class Save extends Banner
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPostValue()) {
            $isCategory = $this->getRequest()->getParam('is_category');
            $model = $this->_bannerFactory->create();
            $storeViewId = $this->getRequest()->getParam('store');

            if ($id = $this->getRequest()->getParam(static::PARAM_CRUD_ID)) {
                $model->load($id);
            }

            /**
             * Save Banner Image
             */
            $imageRequest = $this->getRequest()->getFiles('image');
            if ($imageRequest) {
                if (isset($imageRequest['name'])) {
                    $fileName = $imageRequest['name'];
                } else {
                    $fileName = '';
                }
            } else {
                $fileName = '';
            }

            if ($imageRequest && strlen($fileName)) {
                /*
                 * Save image upload
                 */
                try {
                    $uploader = $this->_uploaderFactory->create(['fileId' => 'image']);

                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

                    /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                    $imageAdapter = $this->_adapterFactory->create();

                    $uploader->addValidateCallback('banner_image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);

                    /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath(\Magestore\Bannerslider\Model\Banner::BASE_MEDIA_PATH)
                    );
                    $data['image'] = \Magestore\Bannerslider\Model\Banner::BASE_MEDIA_PATH."/".$result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            } else {
                if (isset($data['image']) && isset($data['image']['value'])) {
                    if (isset($data['image']['delete'])) {
                        $data['image'] = null;
                        $data['delete_image'] = true;
                    } elseif (isset($data['image']['value'])) {
                        $data['image'] = $data['image']['value'];
                    } else {
                        $data['image'] = null;
                    }
                }
            }


            /**
             * Save Banner mobile image
             */
            $imageRequest = $this->getRequest()->getFiles('mobile_image');
            if ($imageRequest) {
                if (isset($imageRequest['name'])) {
                    $fileName = $imageRequest['name'];
                } else {
                    $fileName = '';
                }
            } else {
                $fileName = '';
            }

            if ($imageRequest && strlen($fileName)) {
                /*
                 * Save image upload
                 */
                try {
                    $uploader = $this->_uploaderFactory->create(['fileId' => 'mobile_image']);

                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

                    /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                    $imageAdapter = $this->_adapterFactory->create();

                    $uploader->addValidateCallback('banner_mobile_image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);

                    /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath(\Magestore\Bannerslider\Model\Banner::BASE_MEDIA_PATH)
                    );
                    $data['mobile_image'] = \Magestore\Bannerslider\Model\Banner::BASE_MEDIA_PATH."/".$result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            } else {
                if (isset($data['mobile_image']) && isset($data['mobile_image']['value'])) {
                    if (isset($data['mobile_image']['delete'])) {
                        $data['mobile_image'] = null;
                        $data['delete_image'] = true;
                    } elseif (isset($data['mobile_image']['value'])) {
                        $data['mobile_image'] = $data['mobile_image']['value'];
                    } else {
                        $data['mobile_image'] = null;
                    }
                }
            }


            /**
             * Save Banner tablet image
             */
            $imageRequest = $this->getRequest()->getFiles('tablet_image');
            if ($imageRequest) {
                if (isset($imageRequest['name'])) {
                    $fileName = $imageRequest['name'];
                } else {
                    $fileName = '';
                }
            } else {
                $fileName = '';
            }

            if ($imageRequest && strlen($fileName)) {
                /*
                 * Save image upload
                 */
                try {
                    $uploader = $this->_uploaderFactory->create(['fileId' => 'tablet_image']);

                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

                    /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                    $imageAdapter = $this->_adapterFactory->create();

                    $uploader->addValidateCallback('banner_tablet_image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);

                    /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath(\Magestore\Bannerslider\Model\Banner::BASE_MEDIA_PATH)
                    );
                    $data['tablet_image'] = \Magestore\Bannerslider\Model\Banner::BASE_MEDIA_PATH."/".$result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            } else {
                if (isset($data['tablet_image']) && isset($data['tablet_image']['value'])) {
                    if (isset($data['tablet_image']['delete'])) {
                        $data['tablet_image'] = null;
                        $data['delete_image'] = true;
                    } elseif (isset($data['tablet_image']['value'])) {
                        $data['tablet_image'] = $data['tablet_image']['value'];
                    } else {
                        $data['tablet_image'] = null;
                    }
                }
            }


            /**
             * -------------------------------------
             */
            /** @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate */
            $localeDate = $this->_objectManager->get('Magento\Framework\Stdlib\DateTime\Timezone');

            $data['start_time'] = $localeDate->date($data['start_time'], null, 'UTC')->format('Y-m-d H:i');
            $data['end_time'] = $localeDate->date($data['end_time'],  null, 'UTC')->format('Y-m-d H:i');

            $model->setData($data)
                ->setStoreViewId($storeViewId);

            try {
                $model->save();

                $this->messageManager->addSuccess(__('The banner has been saved.'));
                $this->_getSession()->setFormData(false);

                return $this->_getBackResultRedirect($resultRedirect, $model->getId());
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath(
                '*/*/edit',
                [static::PARAM_CRUD_ID => $this->getRequest()->getParam(static::PARAM_CRUD_ID)]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }
}