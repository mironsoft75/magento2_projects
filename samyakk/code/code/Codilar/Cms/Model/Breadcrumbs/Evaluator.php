<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Cms\Model\Breadcrumbs;


use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsTypeEvaluatorInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterfaceFactory;
use Magento\Cms\Model\PageFactory;
use Codilar\Breadcrumbs\Helper\BreadcrumbHome;

class Evaluator implements BreadcrumbsTypeEvaluatorInterface
{
    /**
     * @var BreadcrumbsResponseDataInterfaceFactory
     */
    private $breadcrumbsResponseDataInterfaceFactory;
    /**
     * @var PageFactory
     */
    private $pageFactory;
    /**
     * @var BreadcrumbHome
     */
    private $breadcrumbHome;

    /**
     * Evaluator constructor.
     * @param BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory
     * @param PageFactory $pageFactory
     * @param BreadcrumbHome $breadcrumbHome
     */
    public function __construct(
        BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory,
        PageFactory $pageFactory,
        BreadcrumbHome $breadcrumbHome
    )
    {
        $this->breadcrumbsResponseDataInterfaceFactory = $breadcrumbsResponseDataInterfaceFactory;
        $this->pageFactory = $pageFactory;
        $this->breadcrumbHome = $breadcrumbHome;
    }

    /**
     * @param int $id
     * @return \Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface[]
     */
    public function getBreadcrumbs($id)
    {
        $responseArray = [];
        $responseArray[] = $this->breadcrumbHome->getHomeData();
        $cmsPage = $this->pageFactory->create()->load($id);
        /** @var BreadcrumbsResponseDataInterface $response */
        $response = $this->breadcrumbsResponseDataInterfaceFactory->create();
        $response->setTitle($cmsPage->getTitle())->setLink("");
        $responseArray[] = $response;
        return $responseArray;
    }
}