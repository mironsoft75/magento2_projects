<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Breadcrumbs\Api;


interface BreadcrumbsManagementInterface
{
    /**
     * @param string $type
     * @param int $id
     * @return \Codilar\Breadcrumbs\Api\Data\BreadcrumbsResponseInterface
     */
    public function getBreadcrumbs($type, $id);
}