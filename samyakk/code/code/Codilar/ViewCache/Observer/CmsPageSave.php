<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 26/7/19
 * Time: 2:52 PM
 */

namespace Codilar\ViewCache\Observer;

use Codilar\ViewCache\Helper\Data;
use Magento\Framework\Event\ObserverInterface;

class CmsPageSave implements ObserverInterface
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
        /** @var \Magento\Cms\Model\Block $block */
        $block = $observer->getEvent()->getData('page');
        $urlKey = $block->getIdentifier();
        $this->helper->urlExecute($urlKey);
    }
}
