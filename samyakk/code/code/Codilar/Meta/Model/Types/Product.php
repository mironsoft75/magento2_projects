<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Meta\Model\Types;

use Codilar\Core\Helper\Product as CoreProductHelper;
use Codilar\Meta\Api\Data\MetaData\MetaInterface;
use Codilar\Meta\Api\Data\MetaDataInterface;
use Codilar\Meta\Api\Data\MetaDataInterfaceFactory;
use Codilar\Meta\Api\MetaManagementInterface;
use Codilar\Meta\Api\MetaManagementInterfaceFactory;
use Codilar\Meta\Api\Types\MetaDataTypeInterface;
use Codilar\Product\Helper\ProductHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Theme\Block\Html\Header\Logo;

class Product implements MetaDataTypeInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var MetaManagementInterfaceFactory
     */
    private $metaManagementInterfaceFactory;
    /**
     * @var MetaDataInterfaceFactory
     */
    private $metaDataInterfaceFactory;
    /**
     * @var Logo
     */
    private $logo;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * @var CoreProductHelper
     */
    private $coreProductHelper;

    /**
     * Product constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param MetaManagementInterfaceFactory $metaManagementInterfaceFactory
     * @param MetaDataInterfaceFactory $metaDataInterfaceFactory
     * @param Logo $logo
     * @param ProductHelper $productHelper
     * @param CoreProductHelper $coreProductHelper
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        MetaManagementInterfaceFactory $metaManagementInterfaceFactory,
        MetaDataInterfaceFactory $metaDataInterfaceFactory,
        Logo $logo,
        ProductHelper $productHelper,
        CoreProductHelper $coreProductHelper
    ) {
        $this->productRepository = $productRepository;
        $this->metaManagementInterfaceFactory = $metaManagementInterfaceFactory;
        $this->metaDataInterfaceFactory = $metaDataInterfaceFactory;
        $this->logo = $logo;
        $this->productHelper = $productHelper;
        $this->coreProductHelper = $coreProductHelper;
    }

    /**
     * @param string $id
     * @return \Codilar\Meta\Api\Data\MetaDataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMetaTypeData($id)
    {
        /** @var MetaDataInterface $data */
        $data = $this->metaDataInterfaceFactory->create();
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->productRepository->getById(
            $this->productHelper->getProductIdByUrlKey($id)
        );
        $title = ($product->getMetaTitle() == null) ? $product->getName() : $product->getMetaTitle();
        $data->setTitle($title);
        $data->setMeta($this->getMetaData($product));
        return $data;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return \Codilar\Meta\Api\Data\MetaData\MetaInterface[]
     */
    public function getMetaData($product)
    {
        $metaDataArray = [];
        /** @var MetaManagementInterface $meta */
        $meta = $this->metaManagementInterfaceFactory->create();
        $metaDataArray[] = $meta->getMetaData("description", $product->getMetaDescription());
        $metaDataArray[] = $meta->getMetaData("keywords", $product->getMetaKeyword());
        $productImage = $product->getMediaGalleryImages()->getFirstItem()->getUrl();
        $image = ($productImage == null) ? $this->logo->getLogoSrc() : $this->coreProductHelper->getImageUrl($product, "meta_product_image");
        $metaDataArray[] = $meta->getMetaData("og:image", $image);
        $metaDataArray[] = $meta->getMetaData("og:image:secure_url", $image);
        $metaDataArray[] = $meta->getMetaData("og:type", 'product');
        $title = ($product->getMetaTitle() == null) ? $product->getName() : $product->getMetaTitle();
        $metaDataArray[] = $meta->getMetaData("og:title", $title);
        return $metaDataArray;
    }
}
