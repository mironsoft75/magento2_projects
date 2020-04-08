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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Test
 * @package Pimcore\ImportExport\Console
 */
class FirstImagePlaceholder extends Command
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
     * FirstImagePlaceholder constructor.
     * @param CollectionFactory $productCollectionFactory
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        ProductRepository $productRepository,
        Action $productAction,
        State $state

    )
    {
        parent::__construct();

        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
        $this->productAction = $productAction;
        $this->state = $state;
    }

    // Magento 2 API Doc  - https://devdocs.magento.com/swagger/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
           
            $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML); // or \Magento\Framework\App\Area::AREA_ADMINHTML, depending on your needs

            $productCollection = $this->productCollectionFactory->create();
            if (count($productCollection)) {
                foreach ($productCollection as $product) {
                    $product = $this->productRepository->getById($product->getId());
                    $mediaGallery = $product->getMediaGallery();
                    if (isset($mediaGallery['images'])) {
                        foreach ($mediaGallery['images'] as $image) {
                            $this->productAction->updateAttributes(
                                [$product->getId()],
                                array(
                                    'image' => $image['file'],
                                    'small_image' => $image['file'],
                                    'thumbnail' => $image['file'],
                                    'swatch_image' => $image['file']
                                ),
                                0
                            );
                            break;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $output->write('<error>' . $e->getMessage() . '</error>');
        }
        return $this;
    }

    protected function configure()
    {
        $this->setName('pimcore:firstimageplaceholder')
            ->setDescription('Pimcore firstimageplaceholder');
        parent::configure();
    }
}