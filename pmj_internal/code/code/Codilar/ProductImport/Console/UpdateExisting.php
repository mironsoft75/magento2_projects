<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 9/7/19
 * Time: 1:24 PM
 */

namespace Codilar\ProductImport\Console;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExisting extends Command
{
    const PRODUCT_SKU = 'product_sku';
    const US_WEBSITE_ID = '2';
    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;
    /**
     * @var Product
     */
    private $modelProduct;
    /**
     * @var Collection
     */
    private $collection;

    public function __construct(
        \Magento\Framework\App\State $appState,
                                Product $modelProduct,
                                Collection $collection
    ) {
        $this->appState = $appState;
        $this->modelProduct = $modelProduct;
        $this->collection = $collection;
        parent::__construct();
    }

    protected function configure()
    {
        /** bin/magento pimcore:product:adduswebsite */
        $this->setName('pimcore:product:adduswebsite')
            ->setDescription('For Adding US Website to product');
        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->appState->setAreaCode('adminhtml');
            $output->writeln("Started");
            $collection = $this->collection;
            foreach ($collection as $data) {
                $productId = $data->getData('entity_id');
                $product = $this->modelProduct->load($productId);
                $websiteIds= $data->getWebsiteIds();
                if (!in_array(self::US_WEBSITE_ID, $websiteIds)) {
                    $websiteIds[] = self::US_WEBSITE_ID;
                    $product->setWebsiteIds($websiteIds);
                    $product->save();
                }
            }
            $output->writeln("Done");

            return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error> Not Added to US Website- {$e->getMessage()} </error>");
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln($e->getTraceAsString());
            }
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }
    }
}
