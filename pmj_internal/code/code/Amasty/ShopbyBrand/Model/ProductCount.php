<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyBrand
 */


namespace Amasty\ShopbyBrand\Model;

/**
 * Class ProductCount
 *
 * @package Amasty\ShopbyBrand\Model
 */
class ProductCount
{
    /**
     * @var null|array
     */
    private $productCount = null;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var \Magento\CatalogSearch\Model\Layer\Category\ItemCollectionProvider
     */
    private $collectionProvider;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @var \Amasty\ShopbyBrand\Helper\Data
     */
    private $brandHelper;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\CatalogSearch\Model\Layer\Category\ItemCollectionProvider $collectionProvider,
        \Amasty\ShopbyBrand\Helper\Data $brandHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->collectionProvider = $collectionProvider;
        $this->messageManager = $messageManager;
        $this->brandHelper = $brandHelper;
    }

    /**
     * TODO: Unit
     *
     * Get brand product count
     *
     * @param int $optionId
     * @return int
     */
    public function get($optionId)
    {
        if ($this->productCount === null) {
            $attrCode = $this->brandHelper->getBrandAttributeCode();

            try {
                $this->productCount = $this->loadProductCount($attrCode);
            } catch (\Magento\Framework\Exception\StateException $e) {
                if (!$this->messageManager->hasMessages()) {
                    $this->messageManager->addErrorMessage(
                        __('Make sure that the root category for current store is anchored')
                    )->addErrorMessage(
                        __('Make sure that "%1" attribute can be used in layered navigation', $attrCode)
                    );
                }
                $this->productCount = [];
            }

        }

        return isset($this->productCount[$optionId]) ? $this->productCount[$optionId]['count'] : 0;
    }

    /**
     * @param string $attrCode
     *
     * @return array
     */
    private function loadProductCount($attrCode)
    {
        $rootCategoryId = $this->storeManager->getStore()->getRootCategoryId();
        $category = $this->categoryRepository->get($rootCategoryId);
        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $collection */
        $collection = $this->collectionProvider->getCollection($category);

        return $collection->addAttributeToSelect($attrCode)
            ->setVisibility([2,4])
            ->getFacetedData($attrCode);
    }
}
