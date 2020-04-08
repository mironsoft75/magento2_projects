<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Helper;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Tagalys\Sync\Model\ResourceModel\Category\CollectionFactory as TagalysCategoryCollection;

class Category extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var TagalysCategoryCollection
     */
    private $tagalysCategoryCollection;

    /**
     * Category constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CategoryRepositoryInterface $categoryRepository
     * @param TagalysCategoryCollection $tagalysCategoryCollection
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository,
        TagalysCategoryCollection $tagalysCategoryCollection
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->tagalysCategoryCollection = $tagalysCategoryCollection;
    }

    /**
     * Get the parent categories fot top menu.
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getParentCategories()
    {
        $rootCategoryId = $this->storeManager->getStore()->getRootCategoryId();
        $rootCategory = $this->categoryRepository->get($rootCategoryId);
        $subCategoryIds = $rootCategory->getChildren();
        $subCategoryIds = explode(',', $subCategoryIds);
        $data = [];
        foreach ($subCategoryIds as $subCategory) {
            try {
                $category = $this->categoryRepository->get($subCategory);
            } catch (NoSuchEntityException $e) {
                $category = false;
            }
            if ($category) {
                if ($category->getIncludeInMenu()) {
                    $data[] = [
                        'id'    => $category->getId(),
                        'position' => $category->getPosition(),
                        'url_key' => $category->getUrlKey()
                    ];
                }
            }
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getTagalysSelectedCategories()
    {
        $collection = $this->tagalysCategoryCollection->create()
            ->addFieldToSelect('category_id')
            ->load();
        $categories = [];
        foreach ($collection as $item) {
            $categories[] = $item->getCategoryId();
        }
        return $categories;
    }
}
