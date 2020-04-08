<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller;

use Amasty\Blog\Helper\Url;
use Magento\Framework\Module\Manager;

class Router implements \Magento\Framework\App\RouterInterface
{
    
    const FLAG_REDIRECT = 'amplog_redirect_flag';
    
    /** @var \Magento\Framework\App\ActionFactory */
    protected $actionFactory;

    /** @var \Magento\Framework\App\ResponseInterface */
    protected $response;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /** @var  Manager */
    protected $moduleManager;

    /** @var  \Magento\Framework\Registry */
    protected $registry;
    /**
     * @var Url
     */
    private $url;
    /**
     * @var \Amasty\Blog\Helper\Settings
     */
    private $settings;
    /**
     * @var \Amasty\Blog\Model\Router\Action
     */
    private $actionRouter;

    /**
     * @var \Magento\Theme\Block\Html\Pager
     */
    private $pager;
    /**
     * @var \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private $cookieManager;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Registry $registry,
        Url $url,
        \Amasty\Blog\Helper\Settings $settings,
        \Amasty\Blog\Model\Router\Action $actionRouter,
        \Magento\Framework\Stdlib\Cookie\PhpCookieManager $cookieManager,
        \Magento\Theme\Block\Html\Pager $pager,
        Manager $moduleManager
    ) {
        $this->actionFactory = $actionFactory;
        $this->response = $response;
        $this->scopeConfig = $scopeConfig;
        $this->moduleManager = $moduleManager;
        $this->registry = $registry;
        $this->url = $url;
        $this->settings = $settings;
        $this->actionRouter = $actionRouter;
        $this->pager = $pager;
        $this->cookieManager = $cookieManager;
    }

    /**
     * Response Current Page
     *
     * @param string $url
     * @return int|boolean
     */
    public function responsePage($url)
    {
        $pattern = "/\/([\d]{1,}){$this->settings->getBlogPostfix()}$/i";
        preg_match_all($pattern, $url, $matches);
        if (count($matches[1])) {
            $page = $matches[1][0];
            if ($page > 1) {
                return (int)$page;
            }
        }
        return false;
    }

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $identifier = $request->getPathInfo();

        if (isset($identifier[0]) && $identifier[0] == '/') {
            $identifier = substr($identifier, 1, strlen($identifier));
        }

        if ($request->getParam($this->pager->getPageVarName())) {
            $wrongPage = $request->getParam($this->pager->getPageVarName());
        } else {
            $wrongPage = 1;
        }

        $page = $this->responsePage($identifier);

        if ($postId = $this->url->getPostId($identifier)) {
            if ($postId && !$this->url->isRightSyntax($identifier, $postId) && ($this->getRedirectFlag() < 3)) {
                $this->actionRouter
                    ->setIsRedirect(true)
                    ->setRedirectFlag($identifier)
                    ->setModuleName('amblog')
                    ->setControllerName('index')
                    ->setActionName('redirect')
                    ->setParam('url', $this->url->getUrl($postId))
                    ->setAlias($identifier)
                    ->setResult(true)
                ;
            } else {

                $this->actionRouter
                    ->setIsRedirect(false)
                    ->setRedirectFlag($identifier)
                    ->setModuleName('amblog')
                    ->setControllerName('index')
                    ->setActionName('post')
                    ->setParam('id', $postId)
                    ->setAlias($identifier)
                    ->setResult(true)
                ;

            }
        } elseif ($categoryId = $this->url->getCategoryId($identifier, $page)) {
            if ($categoryId
                && !$this->url->isRightSyntax(
                    $identifier,
                    $categoryId,
                    \Amasty\Blog\Helper\Url::ROUTE_CATEGORY,
                    $page ? $page : $wrongPage
                )
                && ($this->getRedirectFlag() < 3)
            ) {
                $this->actionRouter
                    ->setIsRedirect(true)
                    ->setRedirectFlag($identifier)
                    ->setModuleName('amblog')
                    ->setControllerName('index')
                    ->setActionName('redirect')
                    ->setParam(
                        'url',
                        $this->url->getUrl(
                            $categoryId,
                            \Amasty\Blog\Helper\Url::ROUTE_CATEGORY,
                            $wrongPage ? $wrongPage : $page
                        )
                    )
                    ->setAlias($identifier)
                    ->setResult(true)
                ;

            } else {

                $this->actionRouter
                    ->setIsRedirect(false)
                    ->setRedirectFlag($identifier)
                    ->setModuleName('amblog')
                    ->setControllerName('index')
                    ->setActionName('category')
                    ->setParam('id', $categoryId)
                    ->setParam($this->pager->getPageVarName(), $page)
                    ->setAlias($identifier)
                    ->setResult(true)
                ;

            }
        } elseif ($tagId = $this->url->getTagId($identifier, $page)) {

            if ($tagId
                && !$this->url->isRightSyntax(
                    $identifier,
                    $tagId,
                    \Amasty\Blog\Helper\Url::ROUTE_TAG,
                    $page ? $page : $wrongPage
                )
                && ($this->getRedirectFlag() < 3)
            ) {
                $this->actionRouter
                    ->setIsRedirect(true)
                    ->setRedirectFlag($identifier)
                    ->setModuleName('amblog')
                    ->setControllerName('index')
                    ->setActionName('redirect')
                    ->setParam(
                        'url',
                        $this->url->getUrl($tagId, \Amasty\Blog\Helper\Url::ROUTE_TAG, $wrongPage ? $wrongPage : $page)
                    )
                    ->setAlias($identifier)
                    ->setResult(true)
                ;

            } else {

                $this->actionRouter
                    ->setIsRedirect(false)
                    ->setRedirectFlag($identifier)
                    ->setModuleName('amblog')
                    ->setControllerName('index')
                    ->setActionName('tag')
                    ->setParam('id', $tagId)
                    ->setParam($this->pager->getPageVarName(), $page)
                    ->setAlias($identifier)
                    ->setResult(true)
                ;

            }
        } elseif ($this->url->isIndexRequest($identifier, $page)) {

            if ($this->url->isIndexRequest($identifier, $page)
                && !$this->url->isRightSyntax(
                    $identifier,
                    null,
                    \Amasty\Blog\Helper\Url::ROUTE_POST,
                    $page ? $page : $wrongPage
                )
                && ($this->getRedirectFlag() < 3)
            ) {

                $this->actionRouter
                    ->setIsRedirect(true)
                    ->setRedirectFlag($identifier)
                    ->setModuleName('amblog')
                    ->setControllerName('index')
                    ->setActionName('redirect')
                    ->setParam(
                        'url',
                        $this->url->getUrl(null, \Amasty\Blog\Helper\Url::ROUTE_POST, $wrongPage ? $wrongPage : $page)
                    )
                    ->setAlias($identifier)
                    ->setResult(true)
                ;

            } else {

                $this->actionRouter
                    ->setIsRedirect(false)
                    ->setRedirectFlag($identifier)
                    ->setModuleName('amblog')
                    ->setControllerName('index')
                    ->setActionName('index')
                    ->setParam($this->pager->getPageVarName(), $page)
                    ->setAlias($identifier)
                    ->setResult(true)
                ;

            }

        } elseif ($this->url->getIsSearchRequest($identifier, $page)) {

            $this->actionRouter
                ->setIsRedirect(false)
                ->setRedirectFlag($identifier)
                ->setModuleName('amblog')
                ->setControllerName('index')
                ->setActionName('search')
                ->setParam($this->pager->getPageVarName(), $page)
                ->setAlias($identifier)
                ->setResult(true)
            ;

        }

        # Result Action
        if ($this->actionRouter->getResult()) {

            # Redirect Flag
            if ($this->actionRouter->getIsRedirect()) {
                $this->redirectFlagUp();
            } else {
                $this->redirectFlagDown();
            }

            # Request Route
            $request
                ->setModuleName($this->actionRouter->getModuleName())
                ->setControllerName($this->actionRouter->getControllerName())
                ->setActionName($this->actionRouter->getActionName())
            ;

            # Alias
            $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $this->actionRouter->getAlias());

            # Transfer Params
            foreach ($this->actionRouter->getParams() as $key => $value) {
                $request->setParam($key, $value);
            }

            return $this->actionFactory->create('Magento\Framework\App\Action\Forward');

        } else {
            return false;
        }
    }

    public function redirectFlagUp()
    {
        $key = self::FLAG_REDIRECT;
        $flag = $this->getRedirectFlag();
        if (!$flag) {
            $this->cookieManager->setPublicCookie($key, 1);
        } else {
            $this->cookieManager->setPublicCookie($key, ++$flag);
        }
        return $this;
    }

    public function redirectFlagDown()
    {
        $key = self::FLAG_REDIRECT;
        $this->cookieManager->deleteCookie($key);
        return $this;
    }
    
    public function getRedirectFlag()
    {
        $key = self::FLAG_REDIRECT;
        return $this->cookieManager->getCookie($key);
    }
}
