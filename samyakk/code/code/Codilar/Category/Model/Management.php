<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Category\Model;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Category\Api\ManagementInterface;
use Codilar\MegaMenu\Api\CategoryRepositoryInterface;
use Codilar\MegaMenu\Api\Data\CategoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Rest\Response;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Management
 * @package Codilar\Category\Model
 */
class Management extends AbstractApi implements ManagementInterface
{
    const ATTR_HOMECATEGORY = "is_homecategory";
    const TITLE = "Shop By Category";
    const SORT_ORDER = 3;

    /**
     * @var CollectionFactory
     */
    private $categoryCollectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * Management constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param CollectionFactory $categoryCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        CollectionFactory $categoryCollectionFactory,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     * @throws LocalizedException
     */
    protected function getCollection()
    {
        try {
            $collection = $this->categoryCollectionFactory->create();
            $collection->removeAttributeToSelect()->addAttributeToSelect(["name", "image", "url_key"]);
            $collection->addAttributeToFilter(self::ATTR_HOMECATEGORY, 1)
                ->setStore($this->storeManager->getStore())->setOrder("position", SortOrder::SORT_ASC);
            return $collection;
        } catch (\Exception $e) {
            throw new LocalizedException(__("Error fetching category collection."));
        }
    }

    /**
     * @return \Codilar\Category\Api\Data\ShopByCategoryDataInterface
     * @throws LocalizedException
     */
    public function getHomepageCategory()
    {
        $collection = $this->getCollection();
        /** @var CategoryInterface[] $categoryData */
        $categoryData = [];
        $sortOrder = self::SORT_ORDER; //TODO: make it customizable
        $title = self::TITLE; //TODO: make it customizable
        $data = [
            "sort_order" => (int)$sortOrder,
            "title" => $title
        ];
        if ($collection->getSize()) {
            /** @var CategoryInterface $item */
            foreach ($collection as $item) {
                $categoryData[] = [
                    "id" => (int)$item->getId(),
                    "name" => $item->getName(),
                    "url_key" => $item->getUrlKey(),
                    "position" => (int)$item->getPosition(),
                    "image_url" => $item->getImageUrl(),
                    "slug" => $item->getUrlKey()
                ];
            }
        }
        $data["categories"] = $categoryData;
        return $this->sendResponse($this->getNewDataObject($data));
    }
}
