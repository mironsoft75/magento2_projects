<?php

namespace Codilar\Product\Observer;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ProductSaveAfterObserver implements ObserverInterface
{
    /**
     * @var TypeListInterface
     */
    private $typeList;

    /**
     * ProductSaveAfterObserver constructor.
     * @param TypeListInterface $typeList
     */
    public function __construct(
        TypeListInterface $typeList
    ) {
        $this->typeList = $typeList;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $types = ['codilar_product_attributes'];
        foreach ($types as $type) {
            $this->typeList->cleanType($type);
        }
    }
}
