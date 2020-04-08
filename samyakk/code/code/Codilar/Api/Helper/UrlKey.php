<?php

namespace Codilar\Api\Helper;

use Codilar\Core\Helper\Data as CoreHelper;
use Codilar\Product\Helper\ProductHelper;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class UrlKey
{
    /**
     * @var CoreHelper
     */
    private $coreHelper;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var ProductHelper
     */
    private $productHelper;

    /**
     * UrlKey constructor.
     * @param CoreHelper $coreHelper
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ProductHelper $productHelper
     */
    public function __construct(
        CoreHelper $coreHelper,
        CategoryRepositoryInterface $categoryRepository,
        ProductHelper $productHelper
    ) {
        $this->coreHelper = $coreHelper;
        $this->categoryRepository = $categoryRepository;
        $this->productHelper = $productHelper;
    }

    /**
     * @param string $urlKeyData
     * @return array
     * @throws NoSuchEntityException
     */
    public function getUrlKeyData($urlKeyData)
    {
        $urlKeys = explode('/', $urlKeyData);
        try {
            $parentId = null;
            foreach ($urlKeys as $urlKey) {
                $id = $this->coreHelper->getEntityIdByUrlKey(\Magento\Catalog\Model\Category::ENTITY, $urlKey, $parentId);
                $parentId = $id;
            }
            $type = "category";
        } catch (NoSuchEntityException $e) {
            try {
                $id = $this->productHelper->getProductIdByUrlKey($urlKeyData);
                $type = "product";
            } catch (NoSuchEntityException $e) {
                try {
                    $id = $this->coreHelper->getEntityIdByUrlKey('cms-page', $urlKeyData);
                    $type = "cms-page";
                } catch (NoSuchEntityException $e) {
                    throw NoSuchEntityException::singleField('url_key', $urlKeyData);
                }
            }
        }
        return [
            "type" => $type,
            "id" => $id
        ];
    }

    /**
     * @param $urlKey
     * @return mixed
     * @throws \Zend_Db_Statement_Exception
     */
    public function getEntityIdByUrlRewrite($urlKey)
    {
        return $this->coreHelper->getEntityIdByUrlRewrite($urlKey);
    }
}
