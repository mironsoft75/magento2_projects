<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 3/1/19
 * Time: 6:05 PM
 */

namespace Codilar\PriceCalculation\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

/**
 * Class StoneInformation
 * @package Codilar\PriceCalculation\Observer
 */
class StoneInformation implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var productRepository
     */
    protected $_productRepository;

    /**
     * StoneInformation constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->_logger = $logger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $this->_productRepository = $observer->getProduct();
            $this->_productRepository->lockAttribute('stone_information');
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}