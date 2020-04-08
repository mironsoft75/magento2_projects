<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Evincemage\Rapnet\Block\Search;

use Magento\Framework\View\Element\Template\Context;
use Evincemage\Rapnet\Helper\Data as Helper;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class ListProduct extends AbstractProduct
{
    /**
     * @var CollectionFactory
     */
    protected $orderCollection;

    /**
     * @var ProductCollection
     */
    protected $_productCollectionFactory;

    /**
     * @var PriceHelper
     */
    protected $priceHelper;

    /**
     * ListProduct constructor.
     * @param Context $context
     * @param SessionManagerInterface $sessionManager
     * @param Helper $helper
     * @param CollectionFactory $orderCollectionFactory
     * @param ProductCollection $productFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        SessionManagerInterface $sessionManager,
        Helper $helper,
        CollectionFactory $orderCollectionFactory,
        ProductCollection $productFactory,
        PriceHelper $priceHelper,
        array $data = []
    )
    {
        $this->orderCollection = $orderCollectionFactory;
        $this->_productCollectionFactory = $productFactory;
        $this->priceHelper = $priceHelper;
        parent::__construct($context,$sessionManager, $helper, $data);
    }

    public function getCertNumber()
    {
        /*$collection = $this->orderCollection->create();
        $productIds = [];
        foreach ($collection as $item)
        {
            if($item->getStatus()->getText() == "Shipped"){
                $productIds[] = $item->getProductId();
            }
        }

        if(!count($productIds)){
            return [];
        }
        $productCollection = $this->_productCollectionFactory->create();
        $productCollection->addAttributeToSelect('rapnet_diamond_certimg');
        $productCollection->addAttributeToFilter('entity_id',['in'=>$productIds]);
        $productCollection->addAttributeToFilter('rapnet_diamond_certimg',['neq'=>'']);
        $productData = $productCollection->getData();
        $certNumber = [];
        foreach ($productData as $product){
            $certNumber[] = $product['rapnet_diamond_certimg'];
        }

        return $certNumber;*/
        return [];
    }

    public function getProductUrl($id)
    {
        return $this->getUrl('diamondsearch/product/view', ['id'=>$id,'_secure' => true]);
    }

    public function getCartUrl($id)
    {
        return $this->getUrl('diamondsearch/cart/add', ['id'=>$id,'_secure' => true]);
    }

    public function getPrice($price)
    {
        return $this->priceHelper->currency($price, true, false);;
    }
}