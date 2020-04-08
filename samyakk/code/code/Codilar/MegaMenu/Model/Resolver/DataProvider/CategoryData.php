<?php

declare(strict_types=1);

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\MegaMenu\Model\Resolver\DataProvider;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CategoryData
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var \Codilar\Core\Helper\Category
     */
    private $categoryHelper;

    /**
     * CategoryData constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Codilar\Core\Helper\Category $categoryHelper
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        \Codilar\Core\Helper\Category $categoryHelper
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryHelper = $categoryHelper;
    }

    /**
     * Get Category Data.
     * @return array
     */
    public function getData()
    {
        try {
            $parentCategories = $this->categoryHelper->getParentCategories();

            $data = [];
            foreach ($parentCategories as $parentCategory) {
                $data[] = $this->getFormattedCategoryData($parentCategory['id']);
            }
            return $data;
        } catch (NoSuchEntityException $e) {
            return [];
        } catch (LocalizedException $e) {
            return [];
        }
    }

    /**
     * @param int $categoryId
     * @return array
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    protected function getFormattedCategoryData($categoryId)
    {
        $category = $this->categoryRepository->get($categoryId);
        /** @var Category $category */
        $data = [
            "id" => $category->getId(),
            "level" => $category->getLevel(),
            "name" => $category->getName(),
            "url_key" => $category->getUrlKey(),
            "show_in_menu" => $category->getIncludeInMenu(),
            "sort_order" => $category->getPosition()
        ];
        try {
            $imageUrl = $category->getImageUrl();
        } catch (LocalizedException $e) {
            $imageUrl = null;
        }
        if ($category->getChildren()) {
            $children = [];
            foreach ($category->getChildrenCategories() as $child) {
                $children[] = $this->getFormattedCategoryData($child->getId());
            }
            $data['children'] = $children;
        } else {
            $data['children'] = null;
        }
        $data['image_url'] = $imageUrl;

        return $data;
    }

    /**
     * @param $data
     */
    protected function log($data)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/graphql.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($data);
    }
}
