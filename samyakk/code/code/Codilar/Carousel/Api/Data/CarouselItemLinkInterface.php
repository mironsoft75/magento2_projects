<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Api\Data;

interface CarouselItemLinkInterface
{
    CONST LINK_TYPE_PRODUCT = "product";
    CONST LINK_TYPE_PRODUCT_LABEL = "Product";
    CONST LINK_TYPE_CATEGORY = "category";
    CONST LINK_TYPE_CATEGORY_LABEL = "Category";
    CONST LINK_TYPE_CMS = "cms";
    CONST LINK_TYPE_CMS_Label = "Cms Page";
    CONST LINK_TYPE_NONE = "none";
    CONST LINK_TYPE_NONE_Label = "None";
    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     * @return \Codilar\Carousel\Api\Data\CarouselItemLinkInterface
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @param string $identifier
     * @return \Codilar\Carousel\Api\Data\CarouselItemLinkInterface
     */
    public function setIdentifier($identifier);
}