<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 4:07 PM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Variantname;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Codilar\MasterTables\Model\VariantName;
use Codilar\MasterTables\Api\VariantNameRepositoryInterface;
use Magento\Framework\Event\Manager;

/**
 * Class InlineEdit
 * @package Codilar\MasterTables\Controller\Adminhtml\VariantName
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonFactory;

    /**
     * @var \Codilar\MasterTables\Model\VariantName
     */
    private $variantName;
    /**
     * @var VariantNameRepositoryInterface
     */
    protected $variantNameRepository;
    /**
     * @var Manager
     */
    protected $eventManager;


    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param VariantName $variantName
     * @param VariantNameRepositoryInterface $variantNameRepository
     * @param Manager $eventManager
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        VariantName $variantName,
        VariantNameRepositoryInterface $variantNameRepository,
        Manager $eventManager

    )
    {
        parent::__construct($context);
        $this->variantName = $variantName;
        $this->jsonFactory = $jsonFactory;
        $this->variantNameRepository = $variantNameRepository;
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
                foreach ($postItems as $value){
                    $postItem=$value;
                }
                foreach (array_keys($postItems) as $id) {
                    $model = $this->variantNameRepository->load($id);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$id]));
                        $this->variantNameRepository->save($model);
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