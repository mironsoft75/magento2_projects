<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/5/19
 * Time: 9:52 AM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Locationname;

use Codilar\MasterTables\Model\LocationNameFactory;
use Codilar\MasterTables\Model\ResourceModel\LocationName as LocationNameResource;
use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Codilar\Rapnet\Controller\Adminhtml\LocationName
 */
class Delete extends Action
{
    /**
     * @var LocationNameFactory
     */
    private $variantNameFactory;
    /**
     * @var LocationNameResource
     */
    private $variantNameResource;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param LocationNameFactory $variantNameFactory
     * @param LocationNameResource $variantNameResource
     */
    public function __construct(
        Action\Context $context,
        LocationNameFactory $variantNameFactory,
        LocationNameResource $variantNameResource
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
        $id = $this->getRequest()->getParam('location_id');
        $model = $this->variantNameFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->variantNameResource->load($model, $id);
        if ($id) {
            try {
                $this->variantNameResource->delete($model);
                $this->messageManager->addSuccess(__('The LocationName has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['location_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find the LocationName to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}