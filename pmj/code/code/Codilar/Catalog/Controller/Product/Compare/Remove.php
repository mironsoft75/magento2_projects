<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 29/1/19
 * Time: 1:14 PM
 */

namespace Codilar\Catalog\Controller\Product\Compare;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Remove extends \Magento\Catalog\Controller\Product\Compare
{
    /**
     * @var JsonFactory
     */
    private $jsonfactory;

    /**
     * Remove constructor.
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
     * @param JsonFactory $jsonfactory
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Catalog\Model\Product\Compare\ItemFactory $compareItemFactory,
    \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory,
    \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Model\Visitor $customerVisitor,
    \Magento\Catalog\Model\Product\Compare\ListCompare $catalogProductCompareList,
    \Magento\Catalog\Model\Session $catalogSession,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    Validator $formKeyValidator,
    PageFactory $resultPageFactory,
    ProductRepositoryInterface $productRepository,
    JsonFactory $jsonfactory
    )
{
    parent::__construct($context, $compareItemFactory, $itemCollectionFactory, $customerSession, $customerVisitor, $catalogProductCompareList, $catalogSession, $storeManager, $formKeyValidator, $resultPageFactory, $productRepository);
    $this->jsonfactory = $jsonfactory;
}

    /**
     * Remove item from compare list
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $jsonResult = $this->jsonfactory->create();

        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId) {
            $storeId = $this->_storeManager->getStore()->getId();
            try {
                $product = $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                $product = null;
            }

            if ($product) {
                /** @var $item \Magento\Catalog\Model\Product\Compare\Item */
                $item = $this->_compareItemFactory->create();
                if ($this->_customerSession->isLoggedIn()) {
                    $item->setCustomerId($this->_customerSession->getCustomerId());
                } elseif ($this->_customerId) {
                    $item->setCustomerId($this->_customerId);
                } else {
                    $item->addVisitorId($this->_customerVisitor->getId());
                }

                $item->loadByProduct($product);
                /** @var $helper \Magento\Catalog\Helper\Product\Compare */
                $helper = $this->_objectManager->get(\Magento\Catalog\Helper\Product\Compare::class);
                if ($item->getId()) {
                    $item->delete();
                    $productName = $this->_objectManager->get(\Magento\Framework\Escaper::class)
                        ->escapeHtml($product->getName());
                    $this->messageManager->addSuccessMessage(
                        __('You removed product %1 from the comparison list.', $productName)
                    );
                    $this->_eventManager->dispatch(
                        'catalog_product_compare_remove_product',
                        ['product' => $item]
                    );
                    $helper->calculate();
                    if (!$this->getRequest()->getParam('isAjax', false)) {
                        $resultRedirect = $this->resultRedirectFactory->create();
                        return $resultRedirect->setRefererOrBaseUrl();
                    }else{
                        return $jsonResult->setData(['success' => true, 'message' => 'Successfully removed from compare']);
                    }
                }
            }
        }
        if (!$this->getRequest()->getParam('isAjax', false)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setRefererOrBaseUrl();
        }else{
            return $jsonResult->setData(['success' => false, 'message' => 'ProductID does not exist']);
        }
    }
}