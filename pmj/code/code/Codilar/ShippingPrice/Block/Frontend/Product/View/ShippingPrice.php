<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28/3/19
 * Time: 5:43 PM
 */

namespace Codilar\ShippingPrice\Block\Frontend\Product\View;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Codilar\ShippingPrice\Model\ShippingPriceRepositoryFactory;

class ShippingPrice extends \Magento\Framework\View\Element\Template
{

    /**
     * @var Registry
     */
    protected $_registry;
    private $shippingPriceRepositoryFactory;

    /**
     * ShippingPrice constructor.
     * @param ShippingPriceRepositoryFactory $shippingPriceRepositoryFactory
     * @param Template\Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct
    (
        ShippingPriceRepositoryFactory $shippingPriceRepositoryFactory,
        Template\Context $context,
        Registry $registry,
        array $data = [])
    {
        $this->shippingPriceRepositoryFactory = $shippingPriceRepositoryFactory;
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getDays($pin)
    {
        /**
         * @var \Codilar\ShippingPrice\Model\ShippingPriceRepositoryFactory $shipping
         */
        $shipping = $this->shippingPriceRepositoryFactory->create();
        $days = $shipping->getDaysByZipId($pin);
        $product=$this->getCurrentProduct();
        $manufacturingDays = $product->getManufacturingDays();
        $day = (int)$days + (int)$manufacturingDays;
        return $day;
    }
}

