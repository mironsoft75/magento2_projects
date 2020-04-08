<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Breadcrumbs\Helper;

use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterfaceFactory;

class BreadcrumbHome
{
    /**
     * @var BreadcrumbsResponseDataInterfaceFactory
     */
    private $breadcrumbsResponseDataInterfaceFactory;

    /**
     * Evaluator constructor.
     * @param BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory
     */
    public function __construct(
        BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory
    )
    {
        $this->breadcrumbsResponseDataInterfaceFactory = $breadcrumbsResponseDataInterfaceFactory;
    }

    /**
     * @return \Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface
     */
    public function getHomeData()
    {
        /** @var BreadcrumbsResponseDataInterface $response */
        $response = $this->breadcrumbsResponseDataInterfaceFactory->create();
        $response->setTitle("Home")->setLink("/");
        return $response;
    }

    /**
     * @param \Magento\Catalog\Model\Category $category
     * @return string
     */
    public function getCategeoryUrl($category) {
        return $category->getUrlKey();
    }
}