<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Helper;

use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Codilar\Offers\Api\HomepageBlocksRepositoryInterface as BlockRepositoryInterface;

class Data extends AbstractHelper
{
    protected $storeManager;
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var ImageBuilder
     */
    private $imageBuilder;
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;
    /**
     * @var Json
     */
    private $serializer;


    /**
     * Data constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param ImageBuilder $imageBuilder
     * @param BlockRepositoryInterface $blockRepository
     * @param Json $serializer
     * @param CollectionFactory $productCollectionFactory
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ImageBuilder $imageBuilder,
        BlockRepositoryInterface $blockRepository,
        Json $serializer,
        CollectionFactory $productCollectionFactory
    )
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->imageBuilder = $imageBuilder;
        $this->blockRepository = $blockRepository;
        $this->serializer = $serializer;
        $this->productCollectionFactory = $productCollectionFactory;
    }


    /**
     * Get offer products.
     *
     * @param $productIds
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getOfferProducts($productIds)
    {
        if (is_array($productIds)) {
            foreach ($productIds as $key => $id) {
                if ($id == "") {
                    unset($productIds[$key]);
                }
            }
        }
        /* @var $productData \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $productData = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => $productIds]);
        return $productData;
    }


    /**
     * Get Discount Label of product.
     *
     * @param $_product
     * @return bool|string
     */
    public function getDiscountLabel($_product)
    {
        /**
         * @var $_product \Magento\Catalog\Model\Product
         */
        $originalPrice = $_product->getPrice();
        $finalPrice = $_product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();

        $percentage = 0;
        if ($originalPrice > $finalPrice) {
            $percentage = number_format(($originalPrice - $finalPrice) * 100 / $originalPrice,0);
        }
        if ($percentage) {
            return $percentage."% Off";
        } else {
            return false;
        }
    }

    /**
     * Get the image html for product.
     *
     * @param $product
     * @param $imageType
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getProductImageHtml($product, $imageType)
    {
        return $this->imageBuilder->setProduct($product)->setImageId($imageType)->create();
    }

    /**
     * @param int $blockId
     * @return array|bool|float|int|null|string
     */
    public function getBlockProducts($blockId)
    {
        if (empty($blockId)) {
            return [];
        }
        $block = $this->blockRepository->getById($blockId);
        try {
            $products = explode(",", $block->getData('block_data'));
            return $products;
        } catch (\Exception $e) {
            return [];
        }
    }
}
