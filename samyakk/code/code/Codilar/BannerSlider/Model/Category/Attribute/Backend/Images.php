<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Model\Category\Attribute\Backend;

class Images extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    const UPLOAD_CATEGORY_MEDIA = 'Codilar\Category\CategoryMedia';
    protected $_uploaderFactory;
    protected $_filesystem;
    protected $_fileUploaderFactory;
    protected $_logger;
    private $imageUploader;

    /**
     * Images constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        $this->_filesystem                = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_logger                      = $logger;
    }

    /**
     * @return mixed
     */
    private function getImageUploader()
    {
        if ($this->imageUploader === NULL) {
            $this->imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get(
                self::UPLOAD_CATEGORY_MEDIA
            );
        }
        return $this->imageUploader;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this|\Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
     */
    public function beforeSave($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();

        if (!$object->hasData($attrCode)) {
            $object->setData($attrCode, NULL);
        } else {
            $values = $object->getData($attrCode);
            if (is_array($values)) {
                if (!empty($values['delete'])) {
                    $object->setData($attrCode, NULL);
                } else {
                    if (isset($values[0]['name']) && isset($values[0]['tmp_name'])) {
                        $object->setData($attrCode, $values[0]['name']);
                    } else {
                        // don't update
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this|\Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
     */
    public function afterSave($object)
    {
        $image = $object->getData($this->getAttribute()->getName(), NULL);

        if ($image !== NULL) {
            try {
                $this->getImageUploader()->moveFileFromTmp($image);
            } catch (\Exception $e) {
                $this->_logger->critical($e);
            }
        }

        return $this;
    }

}
