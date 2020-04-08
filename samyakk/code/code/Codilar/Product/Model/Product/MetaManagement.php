<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Product;


use Codilar\Product\Api\Data\Product\MetaInterface;
use Codilar\Product\Api\Data\Product\MetaInterfaceFactory;
use Codilar\Product\Api\Product\MetaManagementInterface;

class MetaManagement implements MetaManagementInterface
{
    /**
     * @var MetaInterfaceFactory
     */
    private $metaInterfaceFactory;

    /**
     * MetaManagement constructor.
     * @param MetaInterfaceFactory $metaInterfaceFactory
     */
    public function __construct(
        MetaInterfaceFactory $metaInterfaceFactory
    )
    {
        $this->metaInterfaceFactory = $metaInterfaceFactory;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Codilar\Product\Api\Data\Product\MetaInterface
     */
    public function getMetaData($product)
    {
        /** @var \Codilar\Product\Api\Data\Product\MetaInterface $response */
        $response = $this->metaInterfaceFactory->create();
        $response->setMetaTitle($product->getMetaTitle())
            ->setMetaDescription($product->getMetaDescription())
            ->setMetaKeywords($product->getMetaKeyword());
        return $response;
    }
}