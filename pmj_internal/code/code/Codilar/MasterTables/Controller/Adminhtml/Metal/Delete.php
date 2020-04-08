<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/5/19
 * Time: 9:52 AM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Metal;

use Codilar\MasterTables\Model\MetalFactory;
use Codilar\MasterTables\Model\ResourceModel\Metal as MetalResource;
use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Codilar\Rapnet\Controller\Adminhtml\Metal
 */
class Delete extends Action
{
    /**
     * @var MetalFactory
     */
    private $variantNameFactory;
    /**
     * @var MetalResource
     */
    private $variantNameResource;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param MetalFactory $variantNameFactory
     * @param MetalResource $variantNameResource
     */
    public function __construct(
        Action\Context $context,
        MetalFactory $variantNameFactory,
        MetalResource $variantNameResource
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
        $id = $this->getRequest()->getParam('metal_id');
        $model = $this->variantNameFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->variantNameResource->load($model, $id);
        if ($id) {
            try {
                $this->variantNameResource->delete($model);
                $this->messageManager->addSuccess(__('The Metal has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['metal_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find the Metal to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}