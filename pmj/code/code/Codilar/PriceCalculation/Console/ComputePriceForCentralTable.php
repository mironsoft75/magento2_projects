<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 3/1/19
 * Time: 3:17 PM
 */

namespace Codilar\PriceCalculation\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Codilar\PriceCalculation\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\State;
use Psr\Log\LoggerInterface;

/**
 * Class ComputePriceForCentralTable
 * @package Codilar\PriceCalculation\Console
 */
class ComputePriceForCentralTable extends Command
{
    const DIAMOND_PRODUCT = "Diamond Product";
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
     * ComputePriceForCentralTable constructor.
     * @param CollectionFactory $collectionFactory
     * @param Data $data
     * @param State $state
     * @param LoggerInterface $logger
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Data $data,
        State $state,
        LoggerInterface $logger
    )
    {
        $this->_collectionFactory = $collectionFactory;
        $this->_logger = $logger;
        $this->_priceHelper = $data;
        $this->_state = $state;
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
     * @return $this|int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $attributeId = $this->_priceHelper->getAttrSetId(self::DIAMOND_PRODUCT);
        try {
            $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
            /**
             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $products
             */
            $products = $this->_collectionFactory->create();
            $products->addAttributeToSelect('*');
            foreach ($products as $product) {
                $metalPrice = $this->_priceHelper->metalpricecalculation($product);
                $diamondPrice = $this->_priceHelper->stonepricecalculation($product);
                if ($product->getAttributeSetId() != $attributeId) {
                    $grandTotal = $metalPrice + $diamondPrice;
                    $product->setPrice($grandTotal);
                    $product->save();
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }

}