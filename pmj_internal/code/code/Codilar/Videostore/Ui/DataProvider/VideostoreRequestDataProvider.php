<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 24/11/18
 * Time: 12:08 PM
 */

namespace Codilar\Videostore\Ui\DataProvider;


use Magento\Ui\DataProvider\AbstractDataProvider;
use Codilar\Videostore\Model\ResourceModel\VideostoreRequest\CollectionFactory;

class VideostoreRequestDataProvider extends AbstractDataProvider
{
    protected $_videostoreRequest;

    /**
     * VideostoreRequestDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
}