<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Catalog\Model\Breadcrumbs;


use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsTypeEvaluatorInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterfaceFactory;
use Codilar\Breadcrumbs\Helper\BreadcrumbHome;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Data;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\TestFramework\Event\Magento;

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
     * @var Data
     */
    private $catalogData;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Evaluator constructor.
     * @param BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory
     * @param BreadcrumbHome $breadcrumbHome
     * @param Data $catalogData
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory,
        BreadcrumbHome $breadcrumbHome,
        Data $catalogData,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager
    )
    {
        $this->breadcrumbsResponseDataInterfaceFactory = $breadcrumbsResponseDataInterfaceFactory;
        $this->breadcrumbHome = $breadcrumbHome;
        $this->catalogData = $catalogData;
        $this->productRepository = $productRepository;
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
        try {
            $baseUrl = $this->storeManager->getStore()->getBaseUrl();
            $product = $this->productRepository->getById($id);
            $categoryCollection = $product->getCategoryCollection();
            $categoryCollection->clear();
            $categoryCollection->addAttributeToSort('level', $categoryCollection::SORT_ORDER_DESC)
                ->addAttributeToFilter('path', array('like' => "1/" . $this->storeManager->getStore()->getRootCategoryId() . "/%"));
            $breadcrumbCategories = $categoryCollection->getFirstItem()->getParentCategories();
            /** @var \Magento\Catalog\Model\Category $category */
            $linkPaths = [];
            foreach ($breadcrumbCategories as $category) {
                /** @var BreadcrumbsResponseDataInterface $response */
                $response = $this->breadcrumbsResponseDataInterfaceFactory->create();
                $categoryLink = $this->breadcrumbHome->getCategeoryUrl($category);
                $linkPaths[] = $categoryLink;
                $response->setTitle($category->getName())->setLink(implode('/', $linkPaths));
                $responseArray[] = $response;
            }
            /** @var BreadcrumbsResponseDataInterface $response */
            $response = $this->breadcrumbsResponseDataInterfaceFactory->create();
            $response->setTitle($product->getName())->setLink("");
            $responseArray[] = $response;
        } catch (NoSuchEntityException $e) {
        }

        return $responseArray;
    }
}