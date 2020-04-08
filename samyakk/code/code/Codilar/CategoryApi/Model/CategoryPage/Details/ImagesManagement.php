<?php


namespace Codilar\CategoryApi\Model\CategoryPage\Details;


use Codilar\CategoryApi\Api\CategoryPage\Details\ImagesManagementInterface;
use Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterface;
use Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterfaceFactory;
use Codilar\Core\Helper\Product as CoreProductHelper;
class ImagesManagement implements ImagesManagementInterface
{
    /**
     * @var CoreProductHelper
     */
    private $coreProductHelper;
    /**
     * @var ImagesInterfaceFactory
     */
    private $imagesInterfaceFactory;

    /**
     * ImageManagement constructor.
     * @param CoreProductHelper $coreProductHelper
     * @param ImagesInterfaceFactory $imagesInterfaceFactory
     */
    public function __construct(
        CoreProductHelper $coreProductHelper,
        ImagesInterfaceFactory $imagesInterfaceFactory
    )
    {
        $this->coreProductHelper = $coreProductHelper;
        $this->imagesInterfaceFactory = $imagesInterfaceFactory;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterface[]
     */
    public function getImagesData($product)
    {
//        var_dump($this->coreProductHelper->getImageUrl($product));die;
        $images = [];
        /** @var \Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterface $imageInterface */
        $imageInterface = $this->imagesInterfaceFactory->create();
        $imageInterface->setRole("category_image")
            ->setImage($this->coreProductHelper->getImageUrl($product));
        $images[] = $imageInterface;
        /** @var \Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterface $imageInterface */
        $imageInterface = $this->imagesInterfaceFactory->create();
        $imageInterface->setRole('hover_image')
            ->setImage(($product->getData('hover_image') == null) ? "" : $this->coreProductHelper->getImageUrl($product, 'category_hover_image'));
        $images[] = $imageInterface;
        return $images;
    }
}