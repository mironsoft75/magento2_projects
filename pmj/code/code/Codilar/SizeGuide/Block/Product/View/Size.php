<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 22/11/18
 * Time: 11:35 AM
 */
namespace Codilar\SizeGuide\Block\Product\View;
use Magento\Framework\App\Filesystem\DirectoryList;
class Size extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var
     */
    protected $_filesystem;
    const XML_PATH_STATUS = 'sizeguide/general/enable';
    const XML_PATH_SIZE_RING = 'sizeguide/general/ring_attribute_set_id';
    const XML_PATH_SIZE_BANGLE = 'sizeguide/general/bangle_attribute_set_id';


    /**
     * Size constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Eav\Api\AttributeSetRepositoryInterface  $attributeSet,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this-> attributeSet= $attributeSet;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getProductAttributeId()
    {
        $product = $this->_registry->registry('current_product');
        $productAttributeSetId=$product->getAttributeSetId();
        return $productAttributeSetId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->scopeConfig->getValue(self::XML_PATH_STATUS, $storeScope);
    }

    /**
     * @return mixed
     */
    public function getRingAttributeSet()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->scopeConfig->getValue(self::XML_PATH_SIZE_RING, $storeScope);
    }

    /**
     * @return mixed
     */
    public function getBangleAttributeSet()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->scopeConfig->getValue(self::XML_PATH_SIZE_BANGLE, $storeScope);
    }

}