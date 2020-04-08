<?php

namespace Codilar\Customer\Setup;
use \Magento\Eav\Model\ResourceModel\Entity\Attribute\SetFactory as AttributeSetFactory;
use \Magento\Customer\Setup\CustomerSetupFactory;
use \Magento\Eav\Setup\EavSetupFactory;
use \Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\ModuleDataSetupInterface;
use \Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Psr\Log\LoggerInterface;
use \Magento\Customer\Model\Customer;
/**
 * Class UpgradeData
 * @package Codilar\Customer\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private   $eavSetupFactory;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UpgradeData constructor.
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
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // removing country_code and island_code customer attributes
        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->removeAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                'country_code'
            );
            $eavSetup->removeAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                'island_code'
            );

            $this->addCustomerAttribute($setup, 'otp_verified', [
                'type' => 'int',
                'label' => 'Verification Status',
                'input' => 'text',
                'required' => false,
                'default' => 0,
                'visible' => false,
                'user_defined' => false,
                'sort_order' => 1001,
                'position' => 1001,
                'system' => 0
            ]);
        }
    }

    /**
     * @param $setup
     * @param $code
     * @param $data
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function addCustomerAttribute($setup, $code, $data)
    {
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