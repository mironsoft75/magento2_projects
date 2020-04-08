<?php

namespace Codilar\ShippingPrice\Controller\Check;

use Codilar\ShippingPrice\Model\ShippingPriceRepositoryFactory;
use Codilar\ShippingPrice\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * Class Days
 * @package Codilar\ShippingPrice\Controller\Check
 */
class Days extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var Data
     */
    protected $shippingPriceHelper;
    /**
     * @var ShippingPriceRepositoryFactory
     */
    private $shippingPriceRepositoryFactory;
    /**
     * @var
     */
    private $logger;


    /**
     * Days constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Data $shippingPriceHelper
     * @param ShippingPriceRepositoryFactory $shippingPriceRepositoryFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Data $shippingPriceHelper,
        ShippingPriceRepositoryFactory $shippingPriceRepositoryFactory,
        LoggerInterface $logger
    )
    {
        $this->_storeManager = $storeManager;
        $this->shippingPriceHelper = $shippingPriceHelper;
        $this->shippingPriceRepositoryFactory = $shippingPriceRepositoryFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {

        try {
            $pindata = $this->getRequest()->getParams();
            $pin = $pindata["pin"];
            $manufacturingDays = $pindata["days"];
            if ($manufacturingDays) {
                $days = $this->shippingPriceHelper->getDays($pin);
                if ($days) {
                    $totalDays = (int)$days + (int)$manufacturingDays;
                    echo ucwords(__("This product will be delivered in " . $totalDays . " days."));
                } else {
                    echo ucwords(__("Sorry, currently this product is unavailble for delivery to the above pincode."));

                }
            } else {
                echo ucwords(__("Sorry, currently this product is unavailble for delivery to the above pincode."));
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

}
