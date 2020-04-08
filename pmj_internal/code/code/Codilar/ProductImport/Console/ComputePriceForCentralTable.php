<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 3/1/19
 * Time: 3:17 PM
 */

namespace Codilar\ProductImport\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Codilar\ProductImport\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\State;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;


/**
 * Class ComputePriceForCentralTable
 * @package Codilar\ProductImport\Console
 */
class ComputePriceForCentralTable extends Command
{
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var Data
     */
    protected $_priceHelper;
    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;
    /**
     * @var State
     */
    private $_state;
    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepositoryInterface;
    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * ComputePriceForCentralTable constructor.
     *
     * @param CollectionFactory $collectionFactory
     * @param Data $data
     * @param State $state
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Data $data,
        State $state,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->_collectionFactory = $collectionFactory;
        $this->_logger = $logger;
        $this->_priceHelper = $data;
        $this->_state = $state;
        $this->_storeManager = $storeManager;
        $this->_productRepositoryInterface = $productRepository;
        parent::__construct();
    }

    /**
     * configure
     */
    protected function configure()
    {
        $this->setName('codilar:computeprice:forcentraltable')
            ->setDescription('Codilar Compute Price For CentralTable Update');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
            /**
             * Product Collection
             *
             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $products
             */
            $products = $this->_collectionFactory->create();
            $products->addAttributeToSelect('*');
            $stores = $this->_storeManager->getStores(true);
            if ($products->getSize()) {
                foreach ($products as $product) {
                    foreach ($stores as $store) {
                        $this->_storeManager->setCurrentStore($store->getId());
                        $this->_productRepositoryInterface->save($product);
                    }
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }

}