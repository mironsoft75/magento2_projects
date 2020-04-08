<?php

namespace Codilar\Customer\Setup;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set;
use \Magento\Eav\Model\ResourceModel\Entity\Attribute\SetFactory as AttributeSetFactory;
use \Magento\Customer\Setup\CustomerSetupFactory;
use \Magento\Eav\Setup\EavSetupFactory;
use \Magento\Framework\Setup\InstallDataInterface;
use \Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\ModuleDataSetupInterface;
use \Magento\Customer\Model\Customer;
use \Magento\Eav\Api\AttributeRepositoryInterface;

/**
 * Class InstallData
 * @package Codilar\Customer\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var CustomerSetupFactory
     */
    protected $_customerSetupFactory;
    /**
     * @var AttributeSetFactory
     */
    protected $_attributeSetFactory;
    /**
     * @var AttributeRepositoryInterface
     */
    protected $_attributeRepository;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param CustomerSetupFactory $setupFactory
     * @param AttributeSetFactory $attributeSetFactory
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $setupFactory,
        AttributeSetFactory $attributeSetFactory,
        AttributeRepositoryInterface $attributeRepository
    )
    {
        $this->_customerSetupFactory = $setupFactory;
        $this->_attributeSetFactory = $attributeSetFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->_attributeRepository = $attributeRepository;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $setup->startSetup();

        $this->addCustomerAttribute($setup, 'island_code', [
            'type' => 'varchar',
            'label' => 'Island Code',
            'input' => 'select',
            'source' => 'Codilar\Customer\Model\Source\IslandCode',
            'required' => false,
            'visible' => true,
            'user_defined' => false,
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0
        ]);

        $this->addCustomerAttribute($setup, 'phone_number', [
            'type' => 'varchar',
            'label' => 'Phone Number',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => false,
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0
        ]);

        $setup->endSetup();


    }

    /**
     * @param $setup
     * @param $code
     * @param $data
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function addCustomerAttribute($setup, $code, $data){

        /** @var \Magento\Customer\Setup\CustomerSetup $customerSetup */
        $customerSetup = $this->_customerSetupFactory->create(['setup' => $setup]);

        $customerSetup = $this->_customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet Set */
        $attributeSet = $this->_attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(Customer::ENTITY, $code, $data);
        //add attribute to attribute set
        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, $code)
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer', 'customer_account_create', 'customer_account_edit', 'checkout_register'],
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true
            ]);

        $attribute->save();
    }
}