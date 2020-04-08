<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/6/19
 * Time: 10:53 AM
 */

namespace Codilar\ProductImport\Observer;

use Magento\Framework\Event\ObserverInterface;
use Codilar\ProductImport\Helper\Data;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Class UpdateProductStockStatus
 * @package Codilar\ProductImport\Observer
 */
class UpdateProductStockStatus implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var Data
     */
    protected $_priceHelper;
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * SetProductStockStatus constructor.
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     * @param Data $helper
     */
    public function __construct
    (
        RequestInterface $request,
        LoggerInterface $logger,
        Data $helper
    )
    {
        $this->_logger = $logger;
        $this->_priceHelper = $helper;
        $this->_request = $request;


    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            $items = $order->getAllVisibleItems();
            foreach ($items as $item) {
                $this->_priceHelper->updateIndividualProductStockData($item->getProductId());
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }
}