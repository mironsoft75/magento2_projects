<?php

namespace Pimcore\Aces\Controller\Adminhtml\Submodel;

use Pimcore\Aces\Model\AcesSubmodelFactory;
use Pimcore\Aces\Model\ResourceModel\AcesSubmodel as AcesSubmodelResource;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * @package Pimcore\Aces\Controller\Adminhtml\Submodel
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var AcesSubmodelFactory
     */
    private $acesSubmodelFactory;
    /**
     * @var AcesSubmodelResource
     */
    private $acesSubmodelResource;
    
    /**
     * Save constructor.
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param AcesSubmodelFactory $acesSubmodelFactory
     * @param AcesSubmodelResource $acesSubmodelResource
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        AcesSubmodelFactory $acesSubmodelFactory,
        AcesSubmodelResource $acesSubmodelResource
    )
    {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->acesSubmodelFactory = $acesSubmodelFactory;
        $this->acesSubmodelResource = $acesSubmodelResource;
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
            /** @var \Pimcore\Aces\Model\AcesSubmodelFactory $model */
            $model = $this->acesSubmodelFactory->create();
            if (empty($data['id'])) {
                $data['id'] = null;
            }
            if(isset($data['base_vehicle_id'])){
                unset($data['base_vehicle_id']);
            }
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $this->acesSubmodelResource->load($model, $data['id']);
            }
            $model->setData($data);
            try {
                $this->acesSubmodelResource->save($model);
                $this->dataPersistor->clear('pimcore_aces_submodel_data');
                $this->messageManager->addSuccessMessage(__('You saved the data.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('aces/submodel/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __($e->getMessage()));
            }
            $this->dataPersistor->set('pimcore_aces_submodel_data', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
