<?php

/**
 * @package     magento 2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model\Carousel\Item;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Codilar\Carousel\Model\ResourceModel\Carousel\Item\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends AbstractDataProvider
{
    protected $_loadedData;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * DataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $previousData = $this->dataPersistor->get('carousel_item');
        if (!empty($previousData)) {
            $item = $this->collection->getNewEmptyItem();
            $item->setData($previousData);
            $data[$item->getId()] = $item->getData();
            $this->_loadedData = $data;
            $this->dataPersistor->clear('carousel_item');
        } else {
            $identifierArray = $this->getIdentifierArray();
            $items = $this->collection->getItems();
            foreach ($items as $blocks) {
                $link = json_decode($blocks->getData('link'), true);
                $blocks->setData("link_type", $link['type']);
                if ($link['identifier'] != "#") {
                    $blocks->setData($identifierArray[$link['type']], $link['identifier']);
                }
                $this->_loadedData[$blocks->getId()] = $blocks->getData();
            }
        }
        return $this->_loadedData;
    }

    /**
     * @return array
     */
    protected function getIdentifierArray()
    {
        return [
            "product" => "product_data",
            "category" => "category_data",
            "cms" => "cms_data",
            "none" => "#"
        ];
    }
}