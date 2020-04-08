<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 14/12/18
 * Time: 4:19 PM
 */

namespace Codilar\Sitemap\Block;


use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\View\Element\Template\Context;

class Sitemap extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Collection
     */
    protected $productCollection;

    /**
     * Sitemap constructor.
     * @param Context $context
     * @param Collection $productCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Collection $productCollection,
        array $data = []
    )
    {
        $this->productCollection = $productCollection;
        parent::__construct($context, $data);
    }

    /**
     * To Get Product Collection
     * @return object $product
     */
    public function getProductCollection()
    {

        $product = $this->productCollection->addAttributeToSelect('*')->addAttributeToFilter('visibility', array('neq'=>1))
            ->load();
        return $product;
    }


}