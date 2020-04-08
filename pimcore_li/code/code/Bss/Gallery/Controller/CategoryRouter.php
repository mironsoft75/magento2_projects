<?php
/** 
 * BSS Commerce Co. 
 * 
 * NOTICE OF LICENSE 
 * 
 * This source file is subject to the EULA 
 * that is bundled with this package in the file LICENSE.txt. 
 * It is also available through the world-wide-web at this URL: 
 * http://bsscommerce.com/Bss-Commerce-License.txt 
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE 
 * ================================================================= 
 * This package designed for Magento COMMUNITY edition 
 * BSS Commerce does not guarantee correct work of this extension 
 * on any other Magento edition except Magento COMMUNITY edition. 
 * BSS Commerce does not provide extension support in case of 
 * incorrect edition usage. 
 * ================================================================= 
 * 
 * @category   BSS 
 * @package    Bss_Gallery 
 * @author     Extension Team 
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com ) 
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt 
 */ 
namespace Bss\Gallery\Controller;

class CategoryRouter implements \Magento\Framework\App\RouterInterface
{
    protected $actionFactory;

    protected $_categoryFactory;

    protected $categoryHelper;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Bss\Gallery\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Bss\Gallery\Helper\Category $categoryHelper
    )
    {
        $this->actionFactory = $actionFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->categoryHelper = $categoryHelper;
    }

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $url_key = pathinfo($request->getPathInfo())['basename'];
        $category = $this->_categoryFactory->create();
        $category_id = $category->checkUrlKey($url_key);
        if (!$category_id) {
            return null;
        }
        if (!$this->categoryHelper->isEnabledInFrontend()) {
            return null;
        }
        $request->setModuleName('gallery')->setControllerName('cateview')->setActionName('index')->setParam('category_id', $category_id);
        $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $url_key);
        return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
    }
}
