<?php
/**
 * Created by pimcore.
 * Date: 15/9/18
 * Time: 7:43 PM
 */

namespace Pimcore\Aces\Ui\Component\Ymm\Listing;


use Magento\Ui\DataProvider\AbstractDataProvider;
use Pimcore\Aces\Model\ResourceModel\AcesYmm\CollectionFactory;

class GridDataProvider extends AbstractDataProvider
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
        $this->collection = $collectionFactory->create();
    }
}