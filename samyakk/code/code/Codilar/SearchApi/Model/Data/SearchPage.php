<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 19/7/19
 * Time: 7:49 PM
 */

namespace Codilar\SearchApi\Model\Data;

use Codilar\SearchApi\Api\Data\SearchPageInterface;

class SearchPage extends \Magento\Framework\DataObject implements SearchPageInterface
{

    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[]
     */
    public function getProduct()
    {
        return $this->getData('product');
    }

    /**
     * @param \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[] $product
     * @return $this
     */
    public function setProduct($product)
    {
        return $this->setData('product', $product);
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->getData('page');
    }

    /**
     * @param $pageNumber
     * @return $this
     */
    public function setPage($pageNumber)
    {
        return $this->setData('page', $pageNumber);
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->getData('per_page');
    }

    /**
     * @param $pageSize
     * @return $this
     */
    public function setPerPage($pageSize)
    {
        return $this->setData('per_page', $pageSize);
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->getData('total');
    }

    /**
     * @param $total
     * @return $this
     */
    public function setTotal($total)
    {
        return $this->setData('total', $total);
    }
}
