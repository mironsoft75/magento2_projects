<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 4:00 PM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Metal;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Codilar\MasterTables\Model\Metal;
use Codilar\MasterTables\Api\MetalRepositoryInterface;
use Magento\Framework\Event\Manager;


/**
 * Class InlineEdit
 * @package Codilar\MasterTables\Controller\Adminhtml\Metal
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonFactory;

    /**
     * @var \Codilar\MasterTables\Model\Metal
     */
    private $metal;
    /**
     * @var MetalRepositoryInterface
     */
    protected $metalRepositoryInterface;
    /**
     * @var Manager
     */
    protected $eventManager;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param Metal $metal
     * @param MetalRepositoryInterface $metalRepositoryInterface
     * @param Manager $eventManager
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        Metal $metal,
        MetalRepositoryInterface $metalRepositoryInterface,
        Manager $eventManager


    )
    {
        parent::__construct($context);
        $this->metal = $metal;
        $this->jsonFactory = $jsonFactory;
        $this->metalRepositoryInterface = $metalRepositoryInterface;
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
                    /** @var \Magento\Cms\Model\Block $block */
                    $model = $this->metalRepositoryInterface->load($id);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$id]));
                        $this->metalRepositoryInterface->save($model);
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