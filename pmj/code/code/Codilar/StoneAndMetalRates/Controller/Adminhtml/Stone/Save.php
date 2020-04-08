<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 10/11/18
 * Time: 7:36 PM
 */
namespace Codilar\StoneAndMetalRates\Controller\Adminhtml\Stone;

use Codilar\StoneAndMetalRates\Model\StoneAndMetalRatesFactory;
use Codilar\StoneAndMetalRates\Model\ResourceModel\StoneAndMetalRates as StoneAndMetalRatesResource;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Event\ObserverInterface;


class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    private $StoneAndMetalRatesFactory;
    /**
     * @var StoneAndMetalRatesResource
     */
    private $StoneAndMetalRatesResource;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    /**
     * @var ActivityFactory
     */
    protected  $activityFactory;
    /**
     * @var ActivityResource
     */
    protected $activityResource;
    protected $eventManager;


    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        StoneAndMetalRatesFactory $StoneAndMetalRatesFactory,
        StoneAndMetalRatesResource $StoneAndMetalRatesResource,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Event\Manager $eventManager
    )
    {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->StoneAndMetalRatesFactory = $StoneAndMetalRatesFactory;
        $this->StoneAndMetalRatesResource = $StoneAndMetalRatesResource;
        $this->date=$date;
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

            $model = $this->StoneAndMetalRatesFactory->create();

            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }
            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                $this->StoneAndMetalRatesResource->load($model, $data['entity_id']);
            }
            $data['store_id'] = implode(',', $data['store_id']);
            $data['type']='stone';
            $data['unit']='carat';
            $model->setData($data);
            try {
                $this->StoneAndMetalRatesResource->save($model);
                $oldData=$model->getOrigData();
                $this->eventManager->dispatch('codilar_stone_metal_rates_save_after', ['newData' => $data,'oldData'=> $oldData]);
                $this->messageManager->addSuccessMessage(__('You saved the data.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('rate/stone/edit', ['entity_id' => $model->getEntityId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __($e->getMessage()));
            }
            $this->dataPersistor->set('codilar_product_stone_data', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

