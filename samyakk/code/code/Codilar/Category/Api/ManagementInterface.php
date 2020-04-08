<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Category\Api;

/**
 * Interface ManagementInterface
 * @package Codilar\Category\Api
 */
interface ManagementInterface
{
    /**
     * Get Homepage Category
     * @return \Codilar\Category\Api\Data\ShopByCategoryDataInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getHomepageCategory();
}
