<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/11/18
 * Time: 11:38 AM
 */
namespace Codilar\StoneAndMetalRates\Controller\Adminhtml\Metal;
use Magento\Framework\Event\ObserverInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonFactory;

    /**
     * @var \Codilar\StoneAndMetalRates\Model\StoneAndMetalRates
     */
    private $stoneAndMetalRatesModel;
    protected  $date;
    protected $eventManager;

    /**
     * InlineEdit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Codilar\StoneAndMetalRates\Model\StoneAndMetalRates $stoneAndMetalRatesModel
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param Magento\Framework\Event\Manager $eventManager
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Codilar\StoneAndMetalRates\Model\StoneAndMetalRates $stoneAndMetalRatesModel,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Event\Manager $eventManager

    ) {
        parent::__construct($context);
        $this->stoneAndMetalRatesModel = $stoneAndMetalRatesModel;
        $this->jsonFactory = $jsonFactory;
        $this->date=$date;
        $this->eventManager = $eventManager;
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            foreach ($postItems as $value){
                $postItem=$value;
            }
            if (empty($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $id) {
                    /** @var \Magento\Cms\Model\Block $block */
                    $model = $this->stoneAndMetalRatesModel->load($id);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$id]));
                        $model->save();
                        $data=$model->getOrigData();
                        $this->eventManager->dispatch('codilar_stone_metal_rates_save_after', ['newData' => $postItem,'oldData'=>$data]);
                    } catch (\Exception $e) {
                        $messages[] = "[Entity ID: {$id}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}

