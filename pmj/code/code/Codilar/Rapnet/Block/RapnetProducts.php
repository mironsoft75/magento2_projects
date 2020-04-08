<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/3/19
 * Time: 12:40 PM
 */

namespace Codilar\Rapnet\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Codilar\Rapnet\Helper\Data;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;

/**
 * Class RapnetProducts
 * @package Codilar\Rapnet\Block
 */
class RapnetProducts extends \Magento\Framework\View\Element\Template
{
    const DIAMOND_PRODUCT = "Diamond Product";

    /**
     * @var Registry
     */
    protected $_registry;
    /**
     * @var Data
     */
    protected $_rapnetHelper;
    /**
     * @var CollectionFactory
     */
    protected $_attributeSetCollection;


    /**
     * RapnetProducts constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Data $rapnetHelper
     * @param CollectionFactory $attributeSetCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $rapnetHelper,
        CollectionFactory $attributeSetCollection,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_rapnetHelper = $rapnetHelper;
        $this->_attributeSetCollection = $attributeSetCollection;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\View\Element\Template
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * @return mixed
     */

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCertificate($id)
    {
        $diamond = $this->_rapnetHelper->getDiamondById($id);
        return $diamond->getDiamondHasCertificateFile();
    }

    /**
     * @param $id
     * @return mixed
     */

    public function getCertificateNumber($id)
    {
        $diamond = $this->_rapnetHelper->getDiamondById($id);
        return $diamond->getDiamondCertificateNum();
    }

    /**
     * @param $attrSetName
     * @return int
     */
    public function getAttributeSetId($attrSetName)
    {
        /**
         * @var $attributeSet \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection
         */
        $attributeSet = $this->_attributeSetCollection->create()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'attribute_set_name',
            $attrSetName
        );
        foreach ($attributeSet as $attr):
            $attributeSetId = $attr->getAttributeSetId();
        endforeach;
        return $attributeSetId;
    }

    /**
     * @return int
     */
    public function getAttributeId()
    {
        $attributeId = $this->getAttributeSetId(self::DIAMOND_PRODUCT);
        return $attributeId;
    }
}
