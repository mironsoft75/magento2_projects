<?php
/**
 * Created by salman.
 * Date: 5/9/18
 * Time: 7:29 PM
 * Filename: Test.php
 */

namespace Pimcore\ImportExport\Console;

use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\State;
use Magento\Store\Model\StoreManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SetMetaTitle
 * @package Pimcore\ImportExport\Console
 */
class SetMetaTitle extends Command
{

    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var Action
     */
    private $productAction;
    /**
     * @var State
     */
    private $state;
    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * SetMetaTitle constructor.
     * @param CollectionFactory $productCollectionFactory
     * @param ProductRepository $productRepository
     * @param Action $productAction
     * @param State $state
     * @param StoreManager $storeManager
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        ProductRepository $productRepository,
        Action $productAction,
        State $state,
        StoreManager $storeManager
    )
    {
        parent::__construct();
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
        $this->productAction = $productAction;
        $this->state = $state;
        $this->storeManager = $storeManager;
    }

    // Magento 2 API Doc  - https://devdocs.magento.com/swagger/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);
        try {
            $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML); // or \Magento\Framework\App\Area::AREA_ADMINHTML, depending on your needs
            $stores = $this->storeManager->getStores();
            $productCollection = $this->productCollectionFactory->create();
            $output->writeln("<info>Total Number of Products : " . count($productCollection) . "</info>");
            if (count($productCollection)) {
                foreach ($productCollection as $product) {
                    $storeId = 0;
                    $product = $this->productRepository->getById($product->getId());
                    $metaTitle = $product->getMetaTitle();
                    if(!$metaTitle && $product->getName()){
                        $product->setMetaTitle($product->getName());
                        $product->save();
                        $output->writeln("<info>Store Id : " . $storeId . ", Meta title changed: " . $product->getSku() . "</info>");
                    }
                    else{
                        $output->writeln("<info>No Changes : " . $product->getSku() . "</info>");
                    }
                    /*foreach ($stores as $store){
                        $storeId = $store->getId();
                        $product = $this->productRepository->getById($product->getId(),true,$storeId);
                        $metaTitle = $product->getMetaTitle();
                        if(!$metaTitle && $product->getName()){
                            $product->setMetaTitle($product->getName());
                            $product->save();
                            $output->writeln("<info>Store Id : " . $storeId . ", Meta title changed: " . $product->getSku() . "</info>");
                        }
                    }*/
                }
            }
        } catch (\Exception $e) {
            $output->write('<error>' . $e->getMessage() . '</error>');
        }
        return $this;
    }

    protected function configure()
    {
        $this->setName('pimcore:setmetatitle')
            ->setDescription('Pimcore SetMetaTitle To Products');
        parent::configure();
    }
}