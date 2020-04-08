<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/19
 * Time: 12:05 PM
 */

namespace Codilar\ShippingPrice\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Codilar\ShippingPrice\Model\ShippingPriceRepositoryFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Data
 * @package Codilar\ShippingPrice\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @var ShippingPriceRepositoryFactory
     */
    private $shippingPriceRepositoryFactory;

    /**
     * Data constructor.
     * @param ShippingPriceRepositoryFactory $shippingPriceRepositoryFactory
     * @param Context $context
     */
    public function __construct
    (
        ShippingPriceRepositoryFactory $shippingPriceRepositoryFactory,
        Context $context
    )
    {
        $this->shippingPriceRepositoryFactory = $shippingPriceRepositoryFactory;
        parent::__construct($context);
    }

    /**
     * @param $pin
     * @return mixed
     */
    public function getDays($pin)
    {
        /**
         * @var \Codilar\ShippingPrice\Model\ShippingPriceRepositoryFactory $shipping
         */
        $shipping = $this->shippingPriceRepositoryFactory->create();
        return $shipping->getDaysByZipId($pin);

    }
}