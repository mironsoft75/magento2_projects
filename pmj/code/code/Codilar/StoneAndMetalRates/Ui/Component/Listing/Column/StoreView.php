<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 9/11/18
 * Time: 6:10 PM
 */
namespace Codilar\StoneAndMetalRates\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManager;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class StoreView
 * @package Codilar\StoneAndMetalRates\Ui\Component\Listing\Column
 */
class StoreView extends Column
{
    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * StoreView constructor.
     * @param StoreManager       $storeManager
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        StoreManager $storeManager,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $storeIds = $item['store_id'];
                $searchString = ',';
                if (strpos($storeIds, $searchString) !== false) {
                    $stores = explode(",", $storeIds);
                    $storeName = "";
                    foreach ($stores as $storeId) {
                        $storeName .= $this->storeManager->getStore($storeId)->getName() . " <br>";
                    }
                    $item['store_id'] = $storeName;
                } else {
                    if ($storeIds == "0") {
                        $item['store_id'] = 'All Store Views';
                    } else {
                        $item['store_id'] = $this->storeManager->getStore($storeIds)->getName();
                    }
                }
            }
        }


        return $dataSource;
    }

}


