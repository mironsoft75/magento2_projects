<?php

/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Helper;

use Codilar\Api\Helper\Emulator;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\Registry;
use Magento\Catalog\Block\Product\View;

class Product extends AbstractHelper
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var Emulator
     */
    private $emulator;
    /**
     * @var ProductModel\ImageFactory
     */
    private $imageFactory;
    /**
     * @var ImageBuilder
     */
    private $imageBuilder;
    /**
     * @var Registry
     */
    private $registry;
    /**
     * @var View
     */
    private $productBlock;

    /**
     * Product constructor.
     * @param Context $context
     * @param EavSetupFactory $eavSetupFactory
     * @param Emulator $emulator
     * @param ImageFactory $imageFactory
     * @param ImageBuilder $imageBuilder
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        EavSetupFactory $eavSetupFactory,
        Emulator $emulator,
        ImageFactory $imageFactory,
        ImageBuilder $imageBuilder,
        Registry $registry,
        View $productBlock
    )
    {
        parent::__construct($context);
        $this->eavSetupFactory = $eavSetupFactory;
        $this->emulator = $emulator;
        $this->imageFactory = $imageFactory;
        $this->imageBuilder = $imageBuilder;
        $this->registry = $registry;
        $this->productBlock = $productBlock;
    }

    /**
     * @param $attributeCode
     * @param $attributeLabel
     * @param $attributeType
     * @param $attributeInputType
     * @param array $attributeSets
     * @param bool $required
     * @param bool $visible
     * @param int $defaultValue
     * @param bool $searchable
     * @param bool $filterable
     * @param bool $comparable
     * @param bool $visibleOnFront
     * @param bool $usedInProductListing
     * @param bool $unique
     * @param int $isUsedInGrid
     * @param int $isVisibleInGrid
     * @param string $source
     * @param string $backend
     * @param string $frontend
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createProductAttribute(
        $attributeCode,
        $attributeLabel,
        $attributeType,
        $attributeInputType,
        $attributeSets = [],
        $required = true,
        $visible = true,
        $defaultValue = 1,
        $searchable = false,
        $filterable = false,
        $comparable = false,
        $visibleOnFront = true,
        $usedInProductListing = true,
        $unique = false,
        $isUsedInGrid = 1,
        $isVisibleInGrid = 1,
        $source = "",
        $backend = "",
        $frontend = ""
    )
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create();
        $productEntity = $eavSetup->getEntityType(ProductModel::ENTITY);
        $eavSetup->addAttribute(
            $productEntity,
            $attributeCode,
            [
                'type' => $attributeType,
                'backend' => $backend,
                'frontend' => $frontend,
                'label' => $attributeLabel,
                'input' => $attributeInputType,
                'class' => '',
                'source' => $source,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => $visible,
                'required' => $required,
                'user_defined' => true,
                'default' => $defaultValue,
                'searchable' => $searchable,
                'filterable' => $filterable,
                'comparable' => $comparable,
                'visible_on_front' => $visibleOnFront,
                'used_in_product_listing' => $usedInProductListing,
                'unique' => $unique,
                'apply_to' => '',
                'is_used_in_grid' => $isUsedInGrid,
                'is_visible_in_grid'=> $isVisibleInGrid
            ]
        );
        $attributeSetIds = $attributeSets;
        if (!$attributeSetIds) {
            $attributeSetIds = $eavSetup->getAllAttributeSetIds($productEntity);
        }
        foreach ($attributeSetIds as $attributeSetId) {
            $groupId = $eavSetup->getAttributeGroupId($productEntity, $attributeSetId, "product-details");
            $eavSetup->addAttributeToGroup(
                $productEntity,
                $attributeSetId,
                $groupId,
                $attributeCode,
                null
            );
        }
    }

    /**
     * @param ProductModel $_product
     * @return int
     */
    public function getDiscount($_product)
    {
        /**
         * @var $_product \Magento\Catalog\Model\Product
         */
        $originalPrice = $_product->getPrice();
        $finalPrice = $_product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();

        $percentage = 0;
        if ($originalPrice > $finalPrice) {
            $percentage = intval(($originalPrice - $finalPrice) * 100 / $originalPrice);
        }
        if ($percentage) {
            return $percentage;
        } else {
            return 0;
        }
    }

    /**
     * @param ProductModel $_product
     * @return string
     */
    public function getDiscountLabel($_product) {
        $discount = $this->getDiscount($_product);
        return $discount ? $discount."% Off" : "";
    }

    /**
     * @param ProductModel $_product
     * @return string
     */
    public function getThumbnailUrl($_product){
        $this->emulator->startEmulation(Area::AREA_FRONTEND);
        /** @var \Magento\Catalog\Helper\Image $image */
        $image = $this->imageFactory->create()->init($_product, "product_thumbnail_image")
            ->setImageFile($_product->getFile());
        $imageUrl = $image->getUrl();
        $this->emulator->stopEmulation();
        return (string)$imageUrl;
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProductFromRegistry() {
        return $this->productBlock->getProduct();
    }


}