<?php

namespace Pimcore\Aces\Controller\Adminhtml\Ymm;

use Pimcore\Aces\Model\AcesYmmFactory;
use Pimcore\Aces\Model\ResourceModel\AcesYmm as AcesYmmResource;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Pimcore\Aces\Api\AcesSubmodelInterface;
/**
 * Class Save
 * @package Pimcore\Aces\Controller\Adminhtml\Ymm
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var AcesYmmFactory
     */
    private $acesYmmFactory;
    /**
     * @var AcesYmmResource
     */
    private $acesYmmResource;
    /**
     * @var AcesSubmodelInterface
     */
    private $acesSubmodelInterface;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param AcesYmmFactory $acesYmmFactory
     * @param AcesYmmResource $acesYmmResource
     * @param AcesSubmodelInterface $acesSubmodelInterface
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        AcesYmmFactory $acesYmmFactory,
        AcesYmmResource $acesYmmResource,
        AcesSubmodelInterface $acesSubmodelInterface
    )
    {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->acesYmmFactory = $acesYmmFactory;
        $this->acesYmmResource = $acesYmmResource;
        $this->acesSubmodelInterface = $acesSubmodelInterface;
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
            /** @var \Pimcore\Aces\Model\AcesYmmFactory $model */
            $model = $this->acesYmmFactory->create();
            if (empty($data['base_vehicle_id'])) {
                $data['base_vehicle_id'] = null;
            }
            if(isset($data['year_id'])){
                unset($data['year_id']);
            }
            if(isset($data['make_name'])){
                unset($data['make_name']);
            }
            if(isset($data['vehicle_type_name'])){
                unset($data['vehicle_type_name']);
            }
            $id = $this->getRequest()->getParam('base_vehicle_id');
            if ($id) {
                $this->acesYmmResource->load($model, $data['base_vehicle_id']);
            }
            $model->setData($data);
            try {
                $this->acesYmmResource->save($model);
                $submodelCollection = $this->acesSubmodelInterface->getCollection()
                    ->addFieldToFilter('base_vehicle_id',$id);
                if($submodelCollection->getData()){
                    foreach ($submodelCollection as $submodelData){
                        $submodelId = $submodelData->getId();
                        $baseVehicleId = $submodelData->getBaseVehicleId();
                        $subModel = $this->acesSubmodelInterface->load($submodelId,"id");
                        $subModel->setData('model_name',$data['model_name']);
                        try{
                            $this->acesSubmodelInterface->save($subModel);
                        }
                        catch (\Exception $e){
                            print_r($e->getMessage());die;
                        }
                        //print_r($subModel->getData());die;
                    }
                    //($submodelCollection->getData());die;
                }

                $this->dataPersistor->clear('pimcore_aces_ymm_data');
                $this->messageManager->addSuccessMessage(__('You saved the data.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('aces/ymm/edit', ['base_vehicle_id' => $model->getBaseVehicleId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __($e->getMessage()));
            }
            $this->dataPersistor->set('pimcore_aces_ymm_data', $data);
            return $resultRedirect->setPath('*/*/edit', ['base_vehicle_id' => $this->getRequest()->getParam('base_vehicle_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
