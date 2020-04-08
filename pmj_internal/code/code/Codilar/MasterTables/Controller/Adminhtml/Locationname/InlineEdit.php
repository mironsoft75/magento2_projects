<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 3:06 PM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Locationname;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Codilar\MasterTables\Model\LocationName;
use Codilar\MasterTables\Api\LocationNameRepositoryInterface;
use Magento\Framework\Event\Manager;



/**
 * Class InlineEdit
 * @package Codilar\MasterTables\Controller\Adminhtml\LocationName
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonFactory;

    /**
     * @var \Codilar\MasterTables\Model\LocationName
     */
    private $locationName;
    /**
     * @var LocationNameRepositoryInterface
     */
    protected $locationNameRepository;
    /**
     * @var Manager
     */
    protected $eventManager;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param LocationName $locationName
     * @param LocationNameRepositoryInterface $locationNameRepository
     * @param Manager $eventManager
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        LocationName $locationName,
        LocationNameRepositoryInterface $locationNameRepository,
        Manager $eventManager

    )
    {
        parent::__construct($context);
        $this->locationName = $locationName;
        $this->jsonFactory = $jsonFactory;
        $this->locationNameRepository = $locationNameRepository;
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
            if (empty($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach ($postItems as $value) {
                    $postItem = $value;
                }
                foreach (array_keys($postItems) as $id) {
                    $model = $this->locationNameRepository->load($id);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$id]));
                        $this->locationNameRepository->save($model);
                        $this->eventManager->dispatch('codilar_master_tables_save_after', ['newData' => $postItem]);
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