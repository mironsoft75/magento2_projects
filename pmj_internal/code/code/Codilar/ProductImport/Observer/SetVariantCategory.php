<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/7/19
 * Time: 3:56 PM
 */

namespace Codilar\ProductImport\Observer;

use Magento\Framework\Event\ObserverInterface;
use Codilar\ProductImport\Helper\CategoryHelper;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;

class SetVariantCategory implements ObserverInterface
{
    /**
     * Logger Interface
     *
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var CategoryHelper
     */
    protected $_categoryHelper;
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * SetVariantCategory constructor.
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     * @param CategoryHelper $categoryHelper
     */
    public function __construct
    (
        RequestInterface $request,
        LoggerInterface $logger,
        CategoryHelper $categoryHelper
    )
    {
        $this->_logger = $logger;
        $this->_categoryHelper = $categoryHelper;
        $this->_request = $request;


    }

    /**
     * Execute Function
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $this->_categoryHelper->createCategoryForVariantCollection();
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }
}