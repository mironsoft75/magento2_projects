<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Catalog\Api\Data\Product;


interface ImageInterface
{
    /**
     * @return string
     */
    public function getThumbnailImage();

    /**
     * @param string $thumbnailImage
     * @return $this
     */
    public function setThumbnailImage($thumbnailImage);

    /**
     * @return string
     */
    public function getSmallImage();

    /**
     * @param string $smallImage
     * @return $this
     */
    public function setSmallImage($smallImage);

    /**
     * @return string
     */
    public function getBaseImage();

    /**
     * @param string $baseImage
     * @return $this
     */
    public function setBaseImage($baseImage);
}