<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 9:38 PM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Locationname;

use Codilar\MasterTables\Model\LocationNameFactory;
use Codilar\MasterTables\Model\ResourceModel\LocationName as LocationNameResource;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Event\Manager;


/**
 * Class Save
 * @package Codilar\Rapnet\Controller\Adminhtml\UrlRewrite
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var variantNameFactory
     */
    private $variantNameFactory;
    /**
     * @var variantNameResource
     */
    private $variantNameResource;
    /**
     * @var Manager
     */
    protected $eventManager;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param variantNameFactory $variantNameFactory
     * @param variantNameResource $variantNameResource
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        LocationNameFactory $variantNameFactory,
        LocationNameResource $variantNameResource,
        Manager $eventManager

    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->variantNameFactory = $variantNameFactory;
        $this->variantNameResource = $variantNameResource;
        $this->eventManager = $eventManager;

    }

    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \Codilar\Rapnet\Model\ShippingFactory $model */
            $model = $this->variantNameFactory->create();
            if (empty($data['location_id'])) {
                $data['location_id'] = null;
            }

            $id = $this->getRequest()->getParam('location_id');
            if ($id) {
                $this->variantNameResource->load($model, $data['location_id']);
            }

            $model->setData($data);
            try {
                $this->variantNameResource->save($model);
                $this->dataPersistor->clear('codilar_variant_name_data');
                $this->eventManager->dispatch('codilar_master_tables_save_after', ['newData' => $data]);
                $this->messageManager->addSuccessMessage(__('You saved the LocationName.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('mastertables/variantname/edit', ['variant_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __($e->getMessage()));
            }
            $this->dataPersistor->set('codilar_variant_name_data', $data);
            return $resultRedirect->setPath('*/*/edit', ['variant_id' => $this->getRequest()->getParam('variant_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("Codilar_MasterTables::codilar_variantname_save");
    }
}