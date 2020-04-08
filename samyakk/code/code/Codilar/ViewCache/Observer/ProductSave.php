<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 26/7/19
 * Time: 11:06 AM
 */

namespace Codilar\ViewCache\Observer;

use Codilar\ViewCache\Helper\Data;
use Magento\Framework\Event\ObserverInterface;

class ProductSave implements ObserverInterface
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * Productsave constructor.
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getData('product');
        $urlKey = $product->geturlKey();
        $this->helper->urlExecute($urlKey);
    }
}
