<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\MegaMenu\Model;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\MegaMenu\Api\CategoryRepositoryInterface;
use Codilar\MegaMenu\Api\Data\CategoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Rest\Response;

class CategoryRepository extends AbstractApi implements CategoryRepositoryInterface
{
    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var \Codilar\Core\Helper\Category
     */
    private $categoryHelper;
    /**
     * @var Links
     */
    private $linksModel;

    /**
     * CategoryRepository constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param Links $linksModel
     * @param \Codilar\Core\Helper\Category $categoryHelper
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        Links $linksModel,
        \Codilar\Core\Helper\Category $categoryHelper
    ) {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->categoryRepository = $categoryRepository;
        $this->categoryHelper = $categoryHelper;
        $this->linksModel = $linksModel;
    }

    /**
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface[]
     */
    public function getMenuData()
    {
        $data = [];
        try {
            $parentCategories = $this->categoryHelper->getParentCategories();
            foreach ($parentCategories as $parentCategory) {
                $data[$parentCategory['position']] = $this->getFormattedCategoryData($this->categoryRepository->get($parentCategory['id']));
            }
            ksort($data);
            $data = $this->appendStaticLinks($data);
        } catch (LocalizedException $e) {
        }
        return $this->sendResponse($data);
    }

    /**
     * @param \Magento\Framework\DataObject[] $categories
     * @param null|array $staticLinks
     * @return \Magento\Framework\DataObject[]
     */
    protected function appendStaticLinks($categories, $staticLinks = null)
    {
        try {
            if ($staticLinks === null) {
                $staticLinks = array_reverse($this->linksModel->getUsedLinks());
            }
            if ($categories) {
                foreach ($categories as $key => $category) {
                    foreach ($staticLinks as $slKey => $staticLink) {
                        if ($staticLink['sort']['id'] == $category->getData('id')) {
                            $position = null;
                            $link = $this->getNewDataObject();
                            $description = $category->getData('description');
                            $link->setData([
                                'id' => 0,
                                'name' => $staticLink['name'],
                                'level' => intval($category->getData('level')),
                                'url_key' => isset($staticLink['url']) ? $staticLink['url'] : '',
                                'description' => isset($description) ? $description : '',
                                'slug' => '',
                                'include_in_menu' => true,
                                'position' => 0,
                                'children' => [],
                                'image_url' => "",
                                'is_static' => true,
                                'menu_image' => '',
                                'is_tagalys' => isset($staticLink['is_tagalys']) ? $staticLink['is_tagalys'] : false
                            ]);
                            switch ($staticLink['sort']['type']) {
                                case 'before':
                                    $position = $key - 1;
                                    break;
                                case 'after':
                                    $position = $key;
                                    break;
                                case 'parent':
                                    $children = $category->getData('children');
                                    $link->setData('level', $link->getData('level') + 1);
                                    $children[] = $link;
                                    $category->setData('children', $children);
                                    break;
                            }
                            if (!is_null($position)) {
                                array_splice($categories, $position, 0, $link);
                                unset($staticLink[$slKey]);
                            }
                        }
                    }
                    $category->setData('children', $this->appendStaticLinks($category->getData('children'), $staticLinks));
                }
            }
        } catch (\Exception $exception) {
        }
        return $categories;
    }

    /**
     * @param \Magento\Catalog\Model\Category $category
     * @param null|string $parentUrlKey
     * @return \Codilar\MegaMenu\Api\Data\CategoryInterface
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getFormattedCategoryData($category, $parentUrlKey = null)
    {
        $tagalysCategories = $this->categoryHelper->getTagalysSelectedCategories();
        /** @var Category $category */
        $urlKey = $category->getUrlKey();
        $description = $category->getDescription();
        if ($parentUrlKey) {
            $urlKey = $parentUrlKey . '/' . $urlKey;
        }
        $data = [
            "id" => $category->getId(),
            "level" => $category->getLevel(),
            "name" => $category->getName(),
            "url_key" => $urlKey,
            "description" => isset($description) ? $description : '',
            "include_in_menu" => $category->getIncludeInMenu(),
            "position" => $category->getPosition(),
            "slug" => $urlKey,
            "is_static" => false,
            "is_tagalys" => false
        ];
        foreach ($tagalysCategories as $tagalysCategory) {
            if ($tagalysCategory == $data['id']) {
                $data['is_tagalys'] = true;
                break;
            }
        }
        try {
            $menuImage = $category->getImageUrl("home_icon");
            $imageUrl = $category->getImageUrl('image');
        } catch (LocalizedException $e) {
            $imageUrl = "";
            $menuImage = "";
        }
        $data['image_url'] = $imageUrl;
        $data['menu_image'] = $menuImage;
        if ($category->getChildren()) {
            $children = [];
            foreach ($category->getChildrenCategories() as $child) {
                $child = $this->categoryRepository->get($child->getId());
                if ($child->getIncludeInMenu()) {
                    $children[$child->getPosition()] = $this->getFormattedCategoryData($child, $urlKey);
                }
            }
            ksort($children);
            $data['children'] = $children;
        } else {
            $data['children'] = null;
        }

        /** @var CategoryInterface $category */
        $category = $this->getNewDataObject($data);
        return $category;
    }
}
