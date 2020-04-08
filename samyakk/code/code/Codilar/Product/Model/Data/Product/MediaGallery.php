<?php
/**
 *
 * @package     Banner Slider
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Data\Product;

class MediaGallery extends \Magento\Framework\DataObject implements \Codilar\Product\Api\Data\Product\MediaGalleryInterface
{

    /**
     * @return string
     */
    public function getZoomImage()
    {
        return $this->getData('zoom_image');
    }

    /**
     * @param string $zoomImage
     * @return $this
     */
    public function setZoomImage($zoomImage)
    {
        return $this->setData('zoom_image', $zoomImage);
    }

    /**
     * @return string
     */
    public function getBaseImage()
    {
        return $this->getData('base_image');
    }

    /**
     * @param string $baseImage
     * @return $this
     */
    public function setBaseImage($baseImage)
    {
        return $this->setData('base_image', $baseImage);
    }

    /**
     * @return string
     */
    public function getThumbnailImage()
    {
        return $this->getData('thumbnail_image');
    }

    /**
     * @param string $thumbnailImage
     * @return $this
     */
    public function setThumbnailImage($thumbnailImage)
    {
        return $this->setData('thumbnail_image', $thumbnailImage);
    }
}
