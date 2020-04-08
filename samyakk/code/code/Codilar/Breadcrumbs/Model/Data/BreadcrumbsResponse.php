<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Breadcrumbs\Model\Data;

use Codilar\Breadcrumbs\Api\Data\BreadcrumbsResponseInterface;
use Magento\Framework\DataObject;

class BreadcrumbsResponse extends DataObject implements BreadcrumbsResponseInterface
{

    /**
     * @return \Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface[]
     */
    public function getBreadcrumb()
    {
        return $this->getData('breadcrumb');
    }

    /**
     * @param \Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface[] $breadcrumbData
     * @return $this
     */
    public function setBreadcrumb($breadcrumbData)
    {
        return $this->setData('breadcrumb', $breadcrumbData);
    }
}