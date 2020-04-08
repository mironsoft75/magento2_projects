<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 22/7/19
 * Time: 4:35 PM
 */

namespace Codilar\RelatedProducts\Model;

use Codilar\CategoryApi\Api\CategoryPage\DetailsManagementInterface;
use Codilar\RelatedProducts\Api\Data\RelatedProductInterface;
use Codilar\RelatedProducts\Api\RelatedProductRepositoryInterface;
use Codilar\RelatedProducts\Helper\Data;

class RelatedProductRepository implements RelatedProductRepositoryInterface
{
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var RelatedProductInterface
     */
    private $relatedProduct;
    /**
     * @var DetailsManagementInterface
     */
    private $detailsManagement;

    /**
     * RelatedProductRepository constructor.
     * @param Data $helper
     * @param RelatedProductInterface $relatedProduct
     * @param DetailsManagementInterface $detailsManagement
     */
    public function __construct(
                                Data $helper,
                                RelatedProductInterface $relatedProduct,
                                DetailsManagementInterface $detailsManagement
    ) {
        $this->helper = $helper;
        $this->relatedProduct = $relatedProduct;
        $this->detailsManagement = $detailsManagement;
    }

    /**
     * @param int $id
     * @return \Codilar\RelatedProducts\Api\Data\RelatedProductInterface
     */
    public function getRelatedProducts($id)
    {
        $connection = $this->helper->getNewResourceConnection();
        $categoryIdQuery = $this->helper->getProductCategoryId($id);
        $categoryIdResult = $connection->fetchAll($categoryIdQuery);
        $productPrice=0;
        foreach ($categoryIdResult as $item) {
            $categoryId = $item['CategoryId'];
            $productPrice =$item['final_price'];
        }
        if ($productPrice == null) {
            $nullPriceQuery = $this->helper->getNullPrice($id);
            $nullPriceResult = $connection->fetchAll($nullPriceQuery);
            $productPrice = $nullPriceResult[0]['value'];
        }
        /** @var $productPrice */
        /** @var $categoryId */
        $productIdsQuery = $this->helper->getRelatedProductIds($categoryId, $productPrice);
        $productIdsResult = $connection->fetchAll($productIdsQuery);
        $products = [];
        foreach ($productIdsResult as $item) {
            if ($item['ProductId'] != $id) {
                $products[] = $item['ProductId'];
            }
        }
        return $this->relatedProduct->setProduct($this->detailsManagement->getProductsDetails($products));
    }
}
