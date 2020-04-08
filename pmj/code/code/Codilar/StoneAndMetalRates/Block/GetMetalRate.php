<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 10/1/19
 * Time: 6:38 PM
 */

namespace Codilar\StoneAndMetalRates\Block;
use Codilar\StoneAndMetalRates\Model\ResourceModel\StoneAndMetalRates\CollectionFactory;
use Magento\Backend\Block\Template\Context;

/**
 * Class GetMetalRate
 * @package Codilar\StoneAndMetalRates\Block
 */
class GetMetalRate extends \Magento\Framework\View\Element\Template
{

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;
    const TYPE='metal';

    /**
     * GetMetalRate constructor.
     * @param Context $context
     * @param CollectionFactory $stoneAndMetalRatesCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $stoneAndMetalRatesCollectionFactory,
        array $data = []
    )
    {
        $this->_collectionFactory=$stoneAndMetalRatesCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getMetalCollection()
    {
        $collection=$this->_collectionFactory->create()->addFieldToFilter('type','metal')->addFieldToFilter('metal_type','Gold');
        $collection=$collection->getData();
        return $collection;
    }
}