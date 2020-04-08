<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Category\Model\Breadcrumbs;


use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsTypeEvaluatorInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterfaceFactory;
use Codilar\Breadcrumbs\Helper\BreadcrumbHome;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Store\Model\StoreManagerInterface;

class Evaluator implements BreadcrumbsTypeEvaluatorInterface
{

    /**
     * @var BreadcrumbsResponseDataInterfaceFactory
     */
    private $breadcrumbsResponseDataInterfaceFactory;
    /**
     * @var BreadcrumbHome
     */
    private $breadcrumbHome;
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Evaluator constructor.
     * @param BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory
     * @param BreadcrumbHome $breadcrumbHome
     * @param CategoryFactory $categoryFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory,
        BreadcrumbHome $breadcrumbHome,
        CategoryFactory $categoryFactory,
        StoreManagerInterface $storeManager
    )
    {
        $this->breadcrumbsResponseDataInterfaceFactory = $breadcrumbsResponseDataInterfaceFactory;
        $this->breadcrumbHome = $breadcrumbHome;
        $this->categoryFactory = $categoryFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @param int $id
     * @return \Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface[]
     */
    public function getBreadcrumbs($id)
    {
        $responseArray = [];
        $responseArray[] = $this->breadcrumbHome->getHomeData();
        $this->storeManager->setCurrentStore($this->storeManager->getDefaultStoreView()->getId());
        $category = $this->categoryFactory->create()->load($id);
        $parentCategories = $category->getParentCategories();
        /** @var \Magento\Catalog\Model\Category $parentCategory */
        $linkPaths = [];
        foreach ($parentCategories as $parentCategory) {
            if ($parentCategory->getId() == $this->storeManager->getStore()->getRootCategoryId())
                continue;
            /** @var BreadcrumbsResponseDataInterface $response */
            $response = $this->breadcrumbsResponseDataInterfaceFactory->create();
            $categoryLink =  ($parentCategory->getId() == $id) ? "" : $this->breadcrumbHome->getCategeoryUrl($parentCategory);
            if ($categoryLink) {
                $linkPaths[] = $categoryLink;
                $categoryLink = implode('/', $linkPaths);
            }
            $response->setTitle($parentCategory->getName())->setLink($categoryLink);
            $responseArray[] = $response;
        }
        return $responseArray;
    }
}