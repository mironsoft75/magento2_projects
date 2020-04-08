<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Model\Config\Backend;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\Uploader;

class Upload extends \Magento\Config\Model\Config\Backend\Image
{
    /**
     * @return string[]
     */
    protected function getAllowedExtensions() {
        return ['jpg'];
    }

    /**
     * @return \Magento\Framework\Filesystem\Directory\WriteInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function getDirectoryWrite()
    {
        return $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * @param string $uploadDir
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function getUploadDirPath($uploadDir)
    {
        return $this->getDirectoryWrite()->getAbsolutePath($uploadDir);
    }

    /**
     * Save uploaded file before saving config value
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $file = $this->getFileData();
        if (!empty($file)) {
            $uploadDir = $this->_getUploadDir();
            try {
                /** @var Uploader $uploader */
                $uploader = $this->_uploaderFactory->create(['fileId' => $file]);
                $uploader->setAllowedExtensions($this->getAllowedExtensions());
                $uploader->setAllowRenameFiles(false);
                $uploader->addValidateCallback('size', $this, 'validateMaxSize');
                $result = $uploader->save($uploadDir, 'placeholder.jpg');
            } catch (\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__('%1', $e->getMessage()));
            }

            $filename = $result['file'];
            if ($filename) {
                if ($this->_addWhetherScopeInfo()) {
                    $filename = $this->_prependScopeInfo($filename);
                }
                $this->setValue($filename);
            }
        } else {
            if (is_array($value) && !empty($value['delete'])) {
                $this->setValue('');
            } elseif (is_array($value) && !empty($value['value'])) {
                $this->setValue($value['value']);
            } else {
                $this->unsValue();
            }
        }

        return $this;
    }
}