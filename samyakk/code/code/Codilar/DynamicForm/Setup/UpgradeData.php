<?php
/**
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeManagementInterface;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Type;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var AttributeManagementInterface
     */
    private $attributeManagement;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var Type
     */
    private $eavEntityType;


    /**
     * InstallData constructor.
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param AttributeManagementInterface $attributeManagement
     * @param CollectionFactory $collectionFactory
     * @param Type $eavEntityType
     */
    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory
        $eavSetupFactory,
        AttributeManagementInterface $attributeManagement,
        CollectionFactory $collectionFactory,
        Type $eavEntityType
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeManagement = $attributeManagement;
        $this->collectionFactory = $collectionFactory;
        $this->eavEntityType = $eavEntityType;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        if (version_compare($context->getVersion(),'1.0.4') < 0) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                'custom_forms',
                [
                    'type' => 'varchar',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'frontend' => '',
                    'label' => 'Custom Forms',
                    'input' => 'multiselect',
                    'class' => '',
                    'source' => 'Codilar\DynamicForm\Model\Config\Source\Forms',
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => 0,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => '',
                    'is_used_in_grid' => 1,
                    'is_visible_in_grid'=>1
                ]
            );


            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $entityTypeId = $eavSetup->getEntityTypeId(Product::ENTITY);
            $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);
            foreach ($attributeSetIds as $attributeSetId) {
                /**
                 * getAttributeGroupId($entityTypeId, $attributeSetId, "Group_Code");
                 *
                 */
                $groupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, "product-details");
                $eavSetup->addAttributeToGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $groupId,
                    'custom_forms',
                    null
                );
            }
        }
        $installer->endSetup();
    }
}
