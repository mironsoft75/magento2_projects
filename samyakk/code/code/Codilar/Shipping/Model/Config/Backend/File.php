<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Shipping\Model\Config\Backend;


use Magento\Framework\App\Filesystem\DirectoryList;

class File extends \Magento\Config\Model\Config\Backend\File
{

    /**
     * @return \Magento\Framework\Filesystem\Directory\WriteInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function getDirectoryWrite()
    {
        return $this->_filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
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
}