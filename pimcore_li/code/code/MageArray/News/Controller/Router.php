<?php

namespace MageArray\News\Controller;

use MageArray\News\Model\NewscatFactory;
use MageArray\News\Model\NewspostFactory;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Framework\Url;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Router
 * @package MageArray\News\Controller
 */
class Router implements RouterInterface
{
    /**
     * @var
     */
    protected $_dispatched;
    /**
     * @var ActionFactory
     */
    protected $_actionFactory;
    /**
     * @var ManagerInterface
     */
    protected $_eventManager;
    /**
     * @var UrlInterface
     */
    protected $_url;
    /**
     * @var NewspostFactory
     */
    protected $_newspostFactory;
    /**
     * @var NewscatFactory
     */
    protected $_newscatFactory;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var MessageManager
     */
    protected $_messageManager;
    /**
     * @var ResponseInterface
     */
    protected $_response;
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Router constructor.
     * @param ActionFactory $actionFactory
     * @param ManagerInterface $eventManager
     * @param UrlInterface $url
     * @param NewspostFactory $newspostFactory
     * @param NewscatFactory $newscatFactory
     * @param StoreManagerInterface $storeManager
     * @param ResponseInterface $response
     * @param MessageManager $messageManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ActionFactory $actionFactory,
        ManagerInterface $eventManager,
        UrlInterface $url,
        NewspostFactory $newspostFactory,
        NewscatFactory $newscatFactory,
        StoreManagerInterface $storeManager,
        ResponseInterface $response,
        MessageManager $messageManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_actionFactory = $actionFactory;
        $this->_eventManager = $eventManager;
        $this->_url = $url;
        $this->_newspostFactory = $newspostFactory;
        $this->_newscatFactory = $newscatFactory;
        $this->_storeManager = $storeManager;
        $this->_messageManager = $messageManager;
        $this->_response = $response;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->_dispatched) {
            $urlKey = trim($request->getPathInfo(), '/');
            $origUrlKey = $urlKey;
            /** @var Object $condition */
            $condition = new DataObject(
                ['url_key' => $urlKey, 'continue' => true]
            );
            $this->_eventManager->dispatch(
                'magearray_news_controller_router_match_before',
                ['router' => $this, 'condition' => $condition]
            );
            $urlKey = $condition->getUrlKey();
            if ($condition->getRedirectUrl()) {
                $this->_response->setRedirect($condition->getRedirectUrl());
                $request->setDispatched(true);
                return $this->_actionFactory->create(
                    'Magento\Framework\App\Action\Redirect'
                );
            }
            if (!$condition->getContinue()) {
                return null;
            }

            $entities = [
                'author' => [
                    'prefix' => $this->_scopeConfig->getValue(
                        'magearray_news/general/url_prefix',
                        ScopeInterface::SCOPE_STORES
                    ),
                    'cat_prefix' => $this->_scopeConfig->getValue(
                        'magearray_news/general/cat_prefix',
                        ScopeInterface::SCOPE_STORES
                    ),
                    'suffix' => $this->_scopeConfig->getValue(
                        'magearray_news/general/url_suffix',
                        ScopeInterface::SCOPE_STORES
                    ),
                    'list_key' => $this->_scopeConfig->getValue(
                        'magearray_news/general/list_url',
                        ScopeInterface::SCOPE_STORES
                    ),
                    'list_action' => 'index',
                    'controller' => 'index',
                    'action' => 'index',
                    'param' => 'id',
                    'factory' => $this->_newspostFactory,
                ]
            ];

            foreach ($entities as $entity => $settings) {
                if ($settings['list_key']) {
                    if ($urlKey == $settings['list_key']) {
                        $request->setModuleName('news')
                            ->setControllerName($settings['controller'])
                            ->setActionName($settings['list_action']);
                        $request->setAlias(
                            Url::REWRITE_REQUEST_PATH_ALIAS,
                            $urlKey
                        );
                        $this->_dispatched = true;
                        return $this->_actionFactory->create(
                            'Magento\Framework\App\Action\Forward'
                        );
                    } else {
                        $parts = explode('/', $urlKey);
                        if ($parts[0] == 'news_archive') {
                            if ($parts[1]) {
                                $request->setModuleName('news')
                                    ->setControllerName('archive')
                                    ->setActionName('index')
                                    ->setParam('date', $parts[1]);
                                $request->setAlias(
                                    Url::REWRITE_REQUEST_PATH_ALIAS,
                                    $origUrlKey
                                );
                                $request->setDispatched(true);
                                $this->_dispatched = true;
                                return $this->_actionFactory->create(
                                    'Magento\Framework\App\Action\Forward'
                                );
                            }
                        }
                    }
                }
                $parts = "";
                if ($settings['prefix']) {
                    $parts = explode('/', $urlKey);
                    if (
                        (
                            $parts[0] != $settings['cat_prefix']
                            && $parts[0] != $settings['prefix']
                        ) || count($parts) != 2
                    ) {
                        continue;
                    }
                    $urlKey = $parts[1];
                }
                if ($settings['suffix']) {
                    $suffix = substr(
                        $urlKey,
                        -strlen($settings['suffix']) - 1
                    );
                    if ($suffix != '.' . $settings['suffix']) {
                        continue;
                    }
                    $urlKey = substr(
                        $urlKey,
                        0,
                        -strlen($settings['suffix']) - 1
                    );
                }
                $catPath = false;
                if ($settings['cat_prefix']) {
                    $catPrefix = explode('/', $origUrlKey);
                    if (($parts[0] != $settings['cat_prefix'] && $parts[0] != $settings['prefix']) || count($catPrefix) != 2) {
                        continue;
                    }
                    if (!empty($settings['suffix'])
                        && $settings['suffix'] != ""
                    ) {
                        $urlKeyPart = substr(
                            $catPrefix[1],
                            0,
                            -strlen($settings['suffix']) - 1
                        );
                    } else {
                        $urlKeyPart = $catPrefix[1];
                    }
                    $urlKeyCat = $urlKeyPart;
                    if ($parts[0] == $settings['cat_prefix']) {
                        $catPath = true;
                    }
                }
                if ($catPath) {
                    $instanceCat = $this->_newscatFactory->create();
                    $catId = $instanceCat->checkUrlKey($urlKeyCat);
                    if ($catId) {
                        $request->setModuleName('news')
                            ->setControllerName('category')
                            ->setActionName('index')
                            ->setParam('cat', $catId);
                        $request->setAlias(
                            Url::REWRITE_REQUEST_PATH_ALIAS,
                            $origUrlKey
                        );
                        $request->setDispatched(true);
                        $this->_dispatched = true;
                        return $this->_actionFactory->create(
                            'Magento\Framework\App\Action\Forward'
                        );
                    }
                }

                $instance = $settings['factory']->create();
                $id = $instance->checkUrlKey(
                    $urlKey,
                    $this->_storeManager->getStore()->getId()
                );
                if (!$id) {
                    return null;
                }
                $request->setModuleName('news');
                $request->setControllerName('view');
                $request->setActionName('index');
                $request->setParam('id', $id);
                $request->setAlias(
                    Url::REWRITE_REQUEST_PATH_ALIAS,
                    $origUrlKey
                );
                $request->setDispatched(true);
                $this->_dispatched = true;
                return $this->_actionFactory->create(
                    'Magento\Framework\App\Action\Forward'
                );
            }
        } else {
            return null;
        }
    }
}
