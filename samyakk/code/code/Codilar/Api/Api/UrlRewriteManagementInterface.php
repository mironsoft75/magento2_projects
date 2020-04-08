<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Api\Api;

use Magento\Framework\Exception\NoSuchEntityException;

interface UrlRewriteManagementInterface
{
    /**
     * @param string $slug
     * @return \Codilar\Api\Api\Data\EntityDataInterface
     * @throws NoSuchEntityException
     */
    public function getEntityDataBySlug($slug);
}