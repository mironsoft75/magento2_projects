<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyRoot
 */


namespace Amasty\ShopbyRoot\Controller;

use Amasty\ShopbySeo\Helper\Url;
use Amasty\ShopbySeo\Helper\UrlParser;
use Magento\Framework\Module\Manager;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\RequestInterface;

class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    private $actionFactory;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */

    private $response;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */

    private $scopeConfig;

    /**
     * @var  Manager
     */
    private $moduleManager;

    /**
     * @var  \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var  UrlParser
     */
    private $urlParser;

    /**
     * @var  Url
     */
    private $urlHelper;

    /**
     * @var \Amasty\Shopby\Model\Request
     */
    private $amshopbyRequest;

    /**
     * @var string
     */
    private $brandCode;

    /**
     * @var bool
     */
    private $isRedirectToSingleBrand;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    /**
     * @var \Amasty\ShopbyBase\Model\AllowedRoute
     */
    private $allowedRoute;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Amasty\ShopbyBase\Model\AllowedRoute $allowedRoute,
        UrlParser $urlParser,
        Url $urlHelper,
        Manager $moduleManager,
        \Amasty\Shopby\Model\Request $amshopbyRequest
    ) {
        $this->actionFactory = $actionFactory;
        $this->response = $response;
        $this->scopeConfig = $scopeConfig;
        $this->moduleManager = $moduleManager;
        $this->registry = $registry;
        $this->urlParser = $urlParser;
        $this->urlHelper = $urlHelper;
        $this->amshopbyRequest = $amshopbyRequest;
        $this->allowedRoute = $allowedRoute;
        $this->brandCode = $this->scopeConfig
            ->getValue('amshopby_brand/general/attribute_code', ScopeInterface::SCOPE_STORE);
        $this->urlBuilder = $urlBuilder;
    }

    public function match(RequestInterface $request)
    {
        $shopbyPageUrl = $this->scopeConfig->getValue('amshopby_root/general/url', ScopeInterface::SCOPE_STORE);

        $identifier = str_replace(
            $this->scopeConfig->getValue('catalog/seo/category_url_suffix', ScopeInterface::SCOPE_STORE),
            '',
            trim($request->getPathInfo(), '/')
        );

        if ($identifier == $shopbyPageUrl) {
            // Forward Shopby
            if ($this->allowedRoute->isRouteAllowed($request)) {
                $request->setModuleName('amshopby')->setControllerName('index')->setActionName('index');
                $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
                $params = $this->getRequestParams($request);
                $request->setParams($params);
                if ($this->isRedirectToSingleBrand) {
                    return $this->redirectToSingleBrand($request);
                }
                $this->setBrandParamToRequest($params);
                return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
            }
        }

        return false;
    }
    /**
     * @param RequestInterface $request
     * @param bool $isbrandPage
     * @return array
     */
    private function getRequestParams(RequestInterface $request)
    {
        $params = array_merge($this->parseAmShopByParams($request), $request->getParams());
        $params = $this->checkMultibrand($params);
        return $params;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function checkMultibrand($params)
    {
        if ($this->brandCode && isset($params[$this->brandCode])) {
            $brandValue = $params[$this->brandCode];
            if (is_array($brandValue)) {
                $brandValue = array_unshift($brandValue);
            }

            $delimiterPos = strrpos($brandValue, ',');
            if ($delimiterPos) {
                $brandValue = substr($brandValue, $delimiterPos + 1);
                $this->isRedirectToSingleBrand = true;
            }

            $params[$this->brandCode] = $brandValue;
        }

        return $params;
    }

    /**
     * If this page is brand/brand1-brand2-... redirect to brand/brand1
     */
    private function redirectToSingleBrand(RequestInterface $request)
    {
        $route = sprintf(
            '%s/%s/%s',
            $request->getModuleName(),
            $request->getControllerName(),
            $request->getActionName()
        );
        $url = $this->urlBuilder->getUrl($route, ['_query' => $request->getParams()]);
        $this->response->setRedirect($url, \Zend\Http\Response::STATUS_CODE_301);
        $request->setDispatched(true);
        return $this->actionFactory->create(\Magento\Framework\App\Action\Redirect::class);
    }

    /**
     * @param $params
     */
    protected function setBrandParamToRequest($params)
    {
        if (isset($params[$this->brandCode])) {
            $this->amshopbyRequest->setBrandParam(['code' => $this->brandCode, 'value' => [$params[$this->brandCode]]]);
        }
    }

    public function parseAmShopByParams($request)
    {
        $params = [];
        if ($request->getParam('amshopby')) {
            foreach ($request->getParams() as $key => $values) {
                if ($key == 'amshopby') {
                    foreach ($values as $key => $item) {
                        $params[$key] = implode(",", $item);
                    }
                } else {
                    $params[$key] = $values;
                }
            }
        }

        return $params;
    }
}
