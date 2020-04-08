<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/5/19
 * Time: 9:52 AM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Stonebom;

use Codilar\MasterTables\Model\StoneBomFactory;
use Codilar\MasterTables\Model\ResourceModel\StoneBom as StoneBomResource;
use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Codilar\Rapnet\Controller\Adminhtml\StoneBom
 */
class Delete extends Action
{
    /**
     * @var StoneBomFactory
     */
    private $variantNameFactory;
    /**
     * @var StoneBomResource
     */
    private $variantNameResource;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param StoneBomFactory $variantNameFactory
     * @param StoneBomResource $variantNameResource
     */
    public function __construct(
        Action\Context $context,
        StoneBomFactory $variantNameFactory,
        StoneBomResource $variantNameResource
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
        $id = $this->getRequest()->getParam('stone_bom_id');
        $model = $this->variantNameFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->variantNameResource->load($model, $id);
        if ($id) {
            try {
                $this->variantNameResource->delete($model);
                $this->messageManager->addSuccess(__('The StoneBom has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['stone_bom_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find the StoneBom to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}