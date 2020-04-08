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

class CategorySave implements ObserverInterface
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

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $observer->getEvent()->getData('category');
        $urlKey = $category->getUrlkey();
        $this->helper->urlExecute($urlKey);
    }
}
