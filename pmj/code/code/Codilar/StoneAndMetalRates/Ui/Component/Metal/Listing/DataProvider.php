<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/11/18
 * Time: 8:20 PM
 */
namespace Codilar\StoneAndMetalRates\Ui\Component\Metal\Listing;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Codilar\StoneAndMetalRates\Model\ResourceModel\StoneAndMetalRates\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * GridDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create()->addFieldToFilter('type','metal');
    }

}


