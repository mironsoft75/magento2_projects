<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_SeoSingleUrl
 */


namespace Amasty\SeoSingleUrl\Plugin\Catalog\Controller\Product;

use Magento\Catalog\Controller\Product\View as MagentoView;
use Amasty\SeoSingleUrl\Model\Source\Type;
use Magento\Catalog\Block\Breadcrumbs as CatalogBreadcrumbs;

class View
{
    /**
     * @var \Amasty\SeoSingleUrl\Helper\Data
     */
    private $helper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Catalog\Model\Session
     */
    private $catalogSession;

    public function __construct(
        \Amasty\SeoSingleUrl\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
        $this->helper = $helper;
        $this->storeManager = $storeManager;
        $this->catalogSession = $catalogSession;
    }

    public function aroundExecute(
        MagentoView $subject,
        \Closure $proceed
    ) {
        $result = null;
        $request = $subject->getRequest();

        $redirect = $this->helper->getModuleConfig('general/force_redirect');
        $type = $this->helper->getModuleConfig('general/product_url_type');

        if ($redirect && $type !== Type::DEFAULT_RULES) {
            $productId = (int)$request->getParam('id');
            if ($productId) {
                $canonicalUrl = $this->helper->generateSeoUrl($productId, $this->storeManager->getStore()->getId());
                if ($canonicalUrl) {
                    $originalPath = ltrim($request->getOriginalPathInfo(), '/');
                    if ($originalPath && $canonicalUrl !== $originalPath) {
                        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
                        $result = $subject->getResponse();
                        $result->setRedirect($baseUrl . $canonicalUrl, 301)->sendResponse();
                    }

                }
            }
        }

        if (!$result) {
            /* remove wrong category id from request (it is wrong, because we changed category params in url)*/
            $category = $this->catalogSession->getLastVisitedCategoryId();
            $type = $this->helper->getModuleConfig('general/product_url_type');
            if ($type !== Type::DEFAULT_RULES && $category) {
                $request->setParam('category', 0);
            }

            $result = $proceed();

            // CatalogBreadcrumbs block must generated once
            if ($result
                && $result instanceof \Magento\Framework\View\Result\Layout
                && $result->getLayout() 
                && !$result->getLayout()->getBlock('breadcrumbs_0')
            ) {
                $result->getLayout()->createBlock(CatalogBreadcrumbs::class);
            }
        }

        return $result;
    }
}
