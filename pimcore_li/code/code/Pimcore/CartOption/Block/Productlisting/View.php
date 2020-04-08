<?php
/**
 * Created by PhpStorm.
 * User: pimcore
 * Date: 24/9/18
 * Time: 4:19 PM
 */


/**
 * Class View
 */

namespace Pimcore\CartOption\Block\Productlisting;
/**
 * Class View
 * @package Pimcore\CartOption\Block\Productlistin
 *
 */
use Magento\Catalog\Model\Product;
use Pimcore\Product\Helper\Data;

class View extends \Magento\Framework\View\Element\Template
{
    protected $storeManager;
    protected $coreRegistry = null;
    protected $product = null;


    public function __construct (
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->storeManager=$storeManager;
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * It give baseUrl
     * @return String
     */

    public function getShopUrl()
    {

        return $this->storeManager->getStore()->getBaseUrl();

    }

    public function getProduct()
    {
        if (!$this->product) {
            $this->product = $this->coreRegistry->registry('product');
        }
        return $this->product;
    }



}