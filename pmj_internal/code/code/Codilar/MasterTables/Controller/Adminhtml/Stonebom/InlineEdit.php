<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 4:06 PM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Stonebom;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Codilar\MasterTables\Model\StoneBom;
use Codilar\MasterTables\Api\StoneBomRepositoryInterface;
use Codilar\MasterTables\Api\MetalBomRepositoryInterface;
use Magento\Framework\Event\Manager;

/**
 * Class InlineEdit
 * @package Codilar\MasterTables\Controller\Adminhtml\StoneBom
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonFactory;

    /**
     * @var \Codilar\MasterTables\Model\StoneBom
     */
    private $stoneBom;
    /**
     * @var StoneBomRepositoryInterface
     */
    protected $stoneBomRepository;
    /**
     * @var MetalBomRepositoryInterface
     */
    protected $metalBomRepository;
    /**
     * @var Manager
     */
    protected $eventManager;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param StoneBom $stoneBom
     * @param StoneBomRepositoryInterface $stoneBomRepository
     * @param MetalBomRepositoryInterface $metalBomRepository
     * @param Manager $eventManager
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        StoneBom $stoneBom,
        StoneBomRepositoryInterface $stoneBomRepository,
        MetalBomRepositoryInterface $metalBomRepository,
        Manager $eventManager

    )
    {
        parent::__construct($context);
        $this->stoneBom = $stoneBom;
        $this->jsonFactory = $jsonFactory;
        $this->stoneBomRepository = $stoneBomRepository;
        $this->metalBomRepository = $metalBomRepository;
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
                    $model = $this->stoneBomRepository->load($id);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$id]));
                        $this->stoneBomRepository->save($model);
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