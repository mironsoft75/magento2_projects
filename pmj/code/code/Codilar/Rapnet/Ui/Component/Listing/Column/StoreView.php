<?php

namespace Codilar\Rapnet\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManager;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class StoreView
 * @package Codilar\Rapnet\Ui\Component\Listing\Column
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
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $storeId = $item['store_id'];
                if ($storeId == "0") {
                    $item['store_id'] = 'All Store Views';
                } else {
                    $item['store_id'] = $this->storeManager->getStore($storeId)->getName();
                }
            }
        }
        return $dataSource;
    }
}
