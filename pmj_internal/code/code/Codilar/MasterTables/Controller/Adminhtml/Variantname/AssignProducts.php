<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28/11/19
 * Time: 11:05 AM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Variantname;

use Codilar\MasterTables\Api\VariantNameRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Backend\App\Action;
use Codilar\ProductImport\Helper\Data;
use Magento\Catalog\Api\CategoryLinkManagementInterface;

/**
 * Class AssignProducts
 *
 * @package Codilar\MasterTables\Controller\Adminhtml\Variantname
 */
class AssignProducts extends Action
{

    /**
     *  VariantNameRepositoryInterface
     *
     * @var VariantNameRepositoryInterface
     */
    protected $variantNameRepositoryInterface;
    /**
     * Collection Factory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * Data
     *
     * @var Data
     */
    protected $priceHelper;
    /**
     * CategoryLinkManagementInterface
     *
     * @var CategoryLinkManagementInterface
     */
    protected $categoryLinkManagement;

    /**
     * AssignProducts constructor
     *
     * @param VariantNameRepositoryInterface $variantNameRepositoryInterface
     * @param CollectionFactory $collectionFactory
     * @param Data $helper
     * @param CategoryLinkManagementInterface $categoryLinkManagement
     * @param Action\Context $context
     */
    public function __construct(
        VariantNameRepositoryInterface $variantNameRepositoryInterface,
        CollectionFactory $collectionFactory,
        Data $helper,
        CategoryLinkManagementInterface $categoryLinkManagement,
        Action\Context $context
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->variantNameRepositoryInterface = $variantNameRepositoryInterface;
        $this->priceHelper = $helper;
        $this->categoryLinkManagement = $categoryLinkManagement;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('variant_id');
        /**
         * Redirect
         *
         * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
         */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $variant = $this->variantNameRepositoryInterface->getById($id);
                /**
                 * ProductCollection
                 *
                 * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
                 */
                $productCollection = $this->collectionFactory->create();
                $productCollection->
                addAttributeToFilter('variant_type', $variant->getVariantName());
                foreach ($productCollection as $product) {
                    $categoryIds = $this->priceHelper
                        ->getCategoryIds($variant->getVariantName());
                    $this->categoryLinkManagement
                        ->assignProductToCategories(
                            $product->getSku(),
                            $categoryIds
                        );
                }
                $this->messageManager
                    ->addSuccess(__('Products Assign to Category Successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['variant_id' => $id]);
            }
        }
        $this->messageManager->addError(
            __('We can\'t find the VariantName to Assign Products.')
        );
        return $resultRedirect->setPath('*/*/');
    }
}