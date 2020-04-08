<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 29/1/19
 * Time: 1:13 PM
 */

namespace Codilar\Catalog\Controller\Product\Compare;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Helper\Product\Compare;

class Add extends \Magento\Catalog\Controller\Product\Compare
{
    const LIMIT_TO_COMPARE_PRODUCTS = 3;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var Compare
     */
    private $helper;

    /**
     * Add constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Model\Product\Compare\ItemFactory $compareItemFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Visitor $customerVisitor
     * @param \Magento\Catalog\Model\Product\Compare\ListCompare $catalogProductCompareList
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     * @param PageFactory $resultPageFactory
     * @param ProductRepositoryInterface $productRepository
     * @param JsonFactory $jsonFactory
     * @param Compare $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Product\Compare\ItemFactory $compareItemFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Visitor $customerVisitor,
        \Magento\Catalog\Model\Product\Compare\ListCompare $catalogProductCompareList,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Validator $formKeyValidator, PageFactory $resultPageFactory,
        ProductRepositoryInterface $productRepository,
        JsonFactory $jsonFactory,
        Compare $helper
    )
    {
        parent::__construct($context, $compareItemFactory, $itemCollectionFactory, $customerSession, $customerVisitor, $catalogProductCompareList, $catalogSession, $storeManager, $formKeyValidator, $resultPageFactory, $productRepository);
        $this->jsonFactory = $jsonFactory;
        $this->helper = $helper;
    }

    /**
     * Add item to compare list
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
       $jsonResult  = $this->jsonFactory->create();

        if (!$this->getRequest()->getParam('isAjax', false)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setRefererOrBaseUrl();
        }
        $count = $this->helper->getItemCount();
        if($count >= self::LIMIT_TO_COMPARE_PRODUCTS) {
            $this->messageManager->addErrorMessage(
                'Maximum number of products can be compared is 3.'
            );
            return $jsonResult->setData(['success' => false, 'message' => 'Maximum number of products can be compared is 3']);
        }

        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId && ($this->_customerVisitor->getId() || $this->_customerSession->isLoggedIn())) {
            $storeId = $this->_storeManager->getStore()->getId();
            try {
                $product = $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                $product = null;
                return $jsonResult->setData(['success' => false, 'message' => $e->getMessage()]);
            }

            if ($product) {
                $this->_catalogProductCompareList->addProduct($product);
                $productName = $this->_objectManager->get(
                    \Magento\Framework\Escaper::class
                )->escapeHtml($product->getName());
                $this->messageManager->addComplexSuccessMessage(
                    'addCompareSuccessMessage',
                    [
                        'product_name' => $productName,
                        'compare_list_url' => $this->_url->getUrl('catalog/product_compare')
                    ]
                );

                $this->_eventManager->dispatch('catalog_product_compare_add_product', ['product' => $product]);
                $this->_objectManager->get(\Magento\Catalog\Helper\Product\Compare::class)->calculate();

                return $jsonResult->setData(['success' => true, 'message' => 'Successfully addded to compare']);
            }

            $this->_objectManager->get(\Magento\Catalog\Helper\Product\Compare::class)->calculate();
        }
        return $jsonResult->setData(['success' => false, 'message' => 'Something went wrong']);
    }
}