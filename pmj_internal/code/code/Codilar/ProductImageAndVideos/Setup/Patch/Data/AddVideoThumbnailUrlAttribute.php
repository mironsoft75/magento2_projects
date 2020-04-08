<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    pmj-internal
 * @package    pmj-internal
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     pmj-internal
 * @author       Codilar Team
 **/

namespace Codilar\ProductImageAndVideos\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Class AddVideoThumbnailUrlAttribute
 * @package Codilar\ProductImageAndVideos\Setup\Patch\Data
 */
class AddVideoThumbnailUrlAttribute implements DataPatchInterface
{
    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute('catalog_product', 'video_thumbnail_url', [
            'group' => 'Product Details',
            'type' => 'text',
            'label' => 'Video Thumbnail Url',
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            'input' => 'textarea',
            'source' => null,
            'required' => false,
            'sort_order' => 10,
            'is_user_defined' => true,
            'user_defined' => true,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'used_in_product_listing' => true,
            'visible_on_front' => true,
            'system' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}