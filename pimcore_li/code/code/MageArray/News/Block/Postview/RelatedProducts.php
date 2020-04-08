<?php

namespace MageArray\News\Block\Postview;

use MageArray\News\Helper\Data;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Cms\Model\Page;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Module\Manager;

/**
 * Class RelatedProducts
 * @package MageArray\News\Block\Postview
 */
class RelatedProducts extends AbstractProduct implements IdentityInterface
{
    /**
     * @var
     */
    protected $_proCollection;

    /**
     * @var Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * @var CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var Manager
     */
    protected $_moduleManager;
    /**
     * @var Data
     */
    protected $_dataHelper;

    /**
     * RelatedProducts constructor.
     * @param Context $context
     * @param Visibility $catalogProductVisibility
     * @param Manager $moduleManager
     * @param CollectionFactory $productCollectionFactory
     * @param Data $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Visibility $catalogProductVisibility,
        Manager $moduleManager,
        CollectionFactory $productCollectionFactory,
        Data $dataHelper,
        array $data = []
    ) {
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_moduleManager = $moduleManager;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $newspost = $this->getPost();
        $this->_proCollection = $newspost->getRelatedProducts()
            ->addAttributeToSelect('required_options');

        if ($this->_moduleManager->isEnabled('Magento_Checkout')) {
            $this->_addProductAttributesAndPrices($this->_proCollection);
        }

        $this->_proCollection
            ->setVisibility($this->_catalogProductVisibility
                ->getVisibleInCatalogIds());
        $this->_proCollection
            ->setPageSize($this->_dataHelper
                ->getRelatedProductNumber());
        $this->_proCollection
            ->getSelect()->order('re.position', 'ASC');
        $this->_proCollection->load();

        foreach ($this->_proCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function displayProducts()
    {
        return $this->_dataHelper->getRelatedProductDisplay();
    }

    /**
     * @return bool
     */
    public function displayCart()
    {
        return (bool)$this->_dataHelper->getRelatedShowCart();
    }

    /**
     * @return bool
     */
    public function displayWishList()
    {
        return (bool)$this->_dataHelper->getRelatedShowWhishlist();
    }

    /**
     * @return bool
     */
    public function displayCompare()
    {
        return (bool)$this->_dataHelper->getRelatedShowCompare();
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        if (empty($this->_proCollection)) {
            $this->_prepareCollection();
        }
        return $this->_proCollection;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        if (!$this->hasData('post')) {
            $this->setData(
                'post',
                $this->_coreRegistry->registry('current_post')
            );
        }
        return $this->getData('post');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [
            Page::CACHE_TAG . '_relatedproducts_' . $this->getPost()->getId()
        ];
    }
}
