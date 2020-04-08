<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/5/19
 * Time: 9:52 AM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Metalbom;

use Codilar\MasterTables\Model\MetalBomFactory;
use Codilar\MasterTables\Model\ResourceModel\MetalBom as MetalBomResource;
use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Codilar\Rapnet\Controller\Adminhtml\MetalBom
 */
class Delete extends Action
{
    /**
     * @var MetalBomFactory
     */
    private $variantNameFactory;
    /**
     * @var MetalBomResource
     */
    private $variantNameResource;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param MetalBomFactory $variantNameFactory
     * @param MetalBomResource $variantNameResource
     */
    public function __construct(
        Action\Context $context,
        MetalBomFactory $variantNameFactory,
        MetalBomResource $variantNameResource
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
        $id = $this->getRequest()->getParam('metal_bom_id');
        $model = $this->variantNameFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->variantNameResource->load($model, $id);
        if ($id) {
            try {
                $this->variantNameResource->delete($model);
                $this->messageManager->addSuccess(__('The MetalBom has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['metal_bom_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find the MetalBom to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}