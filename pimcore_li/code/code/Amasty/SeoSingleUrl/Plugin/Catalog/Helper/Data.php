<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_SeoSingleUrl
 */


namespace Amasty\SeoSingleUrl\Plugin\Catalog\Helper;

use Amasty\SeoSingleUrl\Model\Source\Breadcrumb;
use Magento\Catalog\Helper\Data as MagentoData;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class Data
{
    /**
     * @var \Amasty\SeoSingleUrl\Helper\Data
     */
    private $helper;

    /**
     * @var CollectionFactory
     */
    private $categoryFactoryCollection;

    public function __construct(
        \Amasty\SeoSingleUrl\Helper\Data $helper,
        CollectionFactory $categoryFactoryCollection
    ) {
        $this->helper = $helper;
        $this->categoryFactoryCollection = $categoryFactoryCollection;
    }

    public function aroundGetBreadcrumbPath(
        MagentoData $subject,
        \Closure $proceed
    ) {
        $type = $this->helper->getModuleConfig('general/breadcrumb');
        $product = $subject->getProduct();
        $result = [];

        if ($type === Breadcrumb::CURRENT_URL && $product) {
            $seoUrl = $this->helper->getSeoUrl($product, $product->getStoreId());
            $urlArray = explode('/', $seoUrl);
            array_pop($urlArray);

            if ($urlArray) {
                $storeId = $product->getStoreId();
                $breadcrumbsIds = $this->getBreadcrumbsPath(
                    $storeId,
                    end($urlArray),
                    $product->getCategoryIds()
                );

                $breadcrumbs = $this->categoryFactoryCollection->create()
                    ->setStore($storeId)
                    ->addNameToResult()
                    ->addAttributeToSelect('url_key')
                    ->addIdFilter($breadcrumbsIds);
                foreach ($breadcrumbs as $breadcrumb) {
                    if (in_array($breadcrumb->getUrlKey(), $urlArray)) {
                        $result['category' . $breadcrumb->getId()] = [
                            'label' => $breadcrumb->getName(),
                            'link' => $breadcrumb->getUrl()
                        ];
                    }
                }

                if ($subject->getProduct()) {
                    $result['product'] = ['label' => $subject->getProduct()->getName()];
                }
            }
        }

        if (!$result) {
            $result = $proceed();
        }

        return  $result;
    }

    private function getBreadcrumbsPath($storeId, $urlKey, $availableIds)
    {
        $productCategory = $this->categoryFactoryCollection->create()
            ->setStore($storeId)
            ->addAttributeToFilter('url_key', $urlKey)
            ->addIdFilter($availableIds)
            ->addOrderField('level')
            ->setPageSize(1)
            ->getFirstItem();

        return explode('/', $productCategory->getPath());
    }
}
