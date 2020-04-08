<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/5/19
 * Time: 9:52 AM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Variantname;

use Codilar\MasterTables\Model\VariantNameFactory;
use Codilar\MasterTables\Model\ResourceModel\VariantName as VariantNameResource;
use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Codilar\Rapnet\Controller\Adminhtml\VariantName
 */
class Delete extends Action
{
    /**
     * @var VariantNameFactory
     */
    private $variantNameFactory;
    /**
     * @var VariantNameResource
     */
    private $variantNameResource;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param VariantNameFactory $variantNameFactory
     * @param VariantNameResource $variantNameResource
     */
    public function __construct(
        Action\Context $context,
        VariantNameFactory $variantNameFactory,
        VariantNameResource $variantNameResource
    ) {
        parent::__construct($context);
        $this->variantNameFactory = $variantNameFactory;
        $this->variantNameResource = $variantNameResource;
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('variant_id');
        $model = $this->variantNameFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->variantNameResource->load($model, $id);
        if ($id) {
            try {
                $this->variantNameResource->delete($model);
                $this->messageManager->addSuccess(__('The VariantName has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['variant_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find the VariantName to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}