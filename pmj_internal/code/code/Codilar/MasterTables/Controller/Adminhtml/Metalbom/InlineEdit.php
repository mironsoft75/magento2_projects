<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 4:03 PM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Metalbom;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Codilar\MasterTables\Model\MetalBom;
use Codilar\MasterTables\Api\MetalBomRepositoryInterface;
use Magento\Framework\Event\Manager;

/**
 * Class InlineEdit
 * @package Codilar\MasterTables\Controller\Adminhtml\MetalBom
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonFactory;

    /**
     * @var \Codilar\MasterTables\Model\MetalBom
     */
    private $metalBom;
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
     * @param MetalBom $metalBom
     * @param MetalBomRepositoryInterface $metalBomRepository
     * @param Manager $eventManager
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        MetalBom $metalBom,
        MetalBomRepositoryInterface $metalBomRepository,
        Manager $eventManager


    )
    {
        parent::__construct($context);
        $this->metalBom = $metalBom;
        $this->jsonFactory = $jsonFactory;
        $this->metalBomRepository = $metalBomRepository;
        $this->eventManager = $eventManager;

    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
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
                    $model = $this->metalBomRepository->load($id);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$id]));
                        $this->metalBomRepository->save($model);
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