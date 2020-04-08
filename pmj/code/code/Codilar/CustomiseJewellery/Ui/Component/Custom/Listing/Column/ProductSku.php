<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 21/12/18
 * Time: 6:51 PM
 */

namespace Codilar\CustomiseJewellery\Ui\Component\Custom\Listing\Column;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Psr\Log\LoggerInterface;

class ProductSku extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     *
     */
    const NAME = 'column.product_sku';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;
    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * ProductSku constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param ProductRepositoryInterface $productRepository
     * @param LoggerInterface $logger
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        ProductRepositoryInterface $productRepository,
        LoggerInterface $logger,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->_productRepository = $productRepository;
        $this->_logger = $logger;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        if (isset($dataSource['data']['items'])) {
            $fieldName = 'product_sku';
            $key = 0;
            foreach ($dataSource['data']['items'] as $item) {
                if($item['request_type']=='customize_existing_design'){
                    try{
                        $sku = $item[$fieldName];
                        $product = $this->_productRepository->get($sku);
                        $productId=$product->getId();
                    }
                    catch (\Magento\Framework\Exception\NoSuchEntityException $e){
                        $this->_logger->debug($e->getMessage());
                        $productId = false;
                        $sku=false;
                    }
                }
                else{
                    $productId = false;
                    $sku=false;
                }

                if (isset($item[$fieldName]) && isset($item['entity_id']) && $productId && $sku) {
                    $url = $this->urlBuilder->getUrl('catalog/product/edit', ['id' => $productId]);
                    $item['product_sku'] = '<a href="' . $url . '" target="_blank">' . $sku . '</a>';
                }
                $dataSource['data']['items'][$key++] = $item;

            }
        }

        return $dataSource;
    }
}
