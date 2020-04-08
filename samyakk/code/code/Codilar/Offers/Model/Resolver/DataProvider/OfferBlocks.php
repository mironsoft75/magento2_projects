<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Model\Resolver\DataProvider;

use Codilar\Offers\Api\Data\HomepageBlocksInterface;
use Codilar\Offers\Api\HomepageBlocksRepositoryInterface;
use Magento\Catalog\Model\Product;

class OfferBlocks
{
    /**
     * @var HomepageBlocksRepositoryInterface
     */
    private $homepageBlocksRepository;
    /**
     * @var \Codilar\Core\Helper\Product
     */
    private $productHelper;

    /**
     * OfferBlocks constructor.
     * @param HomepageBlocksRepositoryInterface $homepageBlocksRepository
     * @param \Codilar\Core\Helper\Product $productHelper
     */
    public function __construct(
        HomepageBlocksRepositoryInterface $homepageBlocksRepository,
        \Codilar\Core\Helper\Product $productHelper
    )
    {
        $this->homepageBlocksRepository = $homepageBlocksRepository;
        $this->productHelper = $productHelper;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $blockData = [];
        $offerBlocks = $this->homepageBlocksRepository->getCollection();
        if ($offerBlocks->getSize()) {
            /** @var HomepageBlocksInterface $offerBlock */
            foreach ($offerBlocks as $offerBlock) {
                $blockData[] = [
                    "block_id" => $offerBlock->getBlockId(),
                    "title" => $offerBlock->getTitle(),
                    "show_title" => $offerBlock->getShowTitle(),
                    "is_active" => $offerBlock->getIsActive(),
                    "sort_order" => $offerBlock->getSortOrder(),
                    "items" => $this->getProducts($offerBlock)
                ];
            }
        }
        return $blockData;
    }


    /**
     * @param HomepageBlocksInterface $offerBlock
     * @return array|null
     */
    protected function getProducts($offerBlock)
    {
        $products = $offerBlock->getBlockProducts();
        if ($products) {
            $productsData = [];
            /** @var Product $product */
            foreach ($products as $product) {
                $productsData[] = [
                    "id" => $product->getId(),
                    "sku" => $product->getSku(),
                    "name" => $product->getName(),
                    "small_image" => $this->productHelper->getImageUrl($product),
                    "url_key" => $product->getUrlKey(),
                    "product_type" => $product->getTypeId(),
                    "price" => [
                        "regular_price" => [
                                "value" => floatval($product->getPrice()),
                                "currency" => $this->productHelper->getStoreCurrency()
                        ],
                        "special_price" => [
                                "value" => floatval($product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue()),
                                "currency" => $this->productHelper->getStoreCurrency()
                        ]
                    ],
                ];
            }
            return $productsData;
        }
        return null;
    }
}