<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Model\Category;


class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{

    /**
     * @return array
     */
    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        $fields['content'][] = 'mobile_image'; // custom image field

        return $fields;
    }

    /**
     * @param $category
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCategoryThumbUrl($category)
    {
        $url   = false;
        $image = $category->getMobileImage();
        if ($image) {
            if (is_string($image)) {
                $url = $this->_storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . 'catalog/category/' . $image;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }
}
