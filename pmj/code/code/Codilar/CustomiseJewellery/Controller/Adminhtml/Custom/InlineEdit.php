<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 30/11/18
 * Time: 12:12 PM
 */

namespace Codilar\CustomiseJewellery\Controller\Adminhtml\Custom;

/**
 * Class InlineEdit
 * @package Codilar\CustomiseJewellery\Controller\Adminhtml\Custom
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    const REQUEST_TYPE1 = 'upload_any_design';
    const REQUEST_TYPE2 = 'customize_existing_design';
    const REQUEST_TYPE3 = 'work_with_our_designer';
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonFactory;

    /**
     * @var \Codilar\CustomiseJewellery\Model\CustomiseJewellery
     */
    private $customiseJewelleryModel;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * InlineEdit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Codilar\CustomiseJewellery\Model\CustomiseJewellery $customiseJewelleryModel
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date ]
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Codilar\CustomiseJewellery\Model\CustomiseJewellery $customiseJewelleryModel,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    )
    {
        parent::__construct($context);
        $this->customiseJewelleryModel = $customiseJewelleryModel;
        $this->jsonFactory = $jsonFactory;
        $this->date = $date;
    }

    /**
     * @return mixed
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
                foreach (array_keys($postItems) as $id) {
                    /** @var \Magento\Cms\Model\Block $block */
                    $model = $this->customiseJewelleryModel->load($id);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$id]));
                        $originalData = $model->getOrigData();
                        $oldRequestStatus = $originalData['status'];
                        if ($oldRequestStatus == '3') {
                            $messages[] = __('Request Status has been Completed, you can not change status.');
                            $error = true;
                            return $resultJson->setData([

                                'messages' => $messages,
                                'error' => $error
                            ]);
                        }
                        if ($oldRequestStatus == '4') {
                            $messages[] = __('Request Status has been Cancelled, you can not change status.');
                            $error = true;
                            return $resultJson->setData([

                                'messages' => $messages,
                                'error' => $error
                            ]);
                        }
                        $model->setSendEmail(1);
                        $model->setEmailSent(0);
                        $model->save();
                        $messages[] = "[Entity ID: {$id}] data has been updated";
                        $error = false;
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
