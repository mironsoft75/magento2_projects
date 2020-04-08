<?php
/**
 *
 * @package     Banner Slider
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Api\Data\Product;

interface MediaGalleryInterface
{
    /**
     * @return string
     */
    public function getZoomImage();

    /**
     * @param string $zoomImage
     * @return $this
     */
    public function setZoomImage($zoomImage);

    /**
     * @return string
     */
    public function getBaseImage();

    /**
     * @param string $baseImage
     * @return $this
     */
    public function setBaseImage($baseImage);

    /**
     * @return string
     */
    public function getThumbnailImage();

    /**
     * @param string $thumbnailImage
     * @return $this
     */
    public function setThumbnailImage($thumbnailImage);
}
