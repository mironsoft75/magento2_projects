<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller\Adminhtml\Form;


use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Api\AbstractExtensibleObject;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

class InlineEdit extends Action
{
    const ADMIN_RESOURCE = "Codilar_DynamicForm::form";
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * InlineEdit constructor.
     * @param Action\Context $context
     * @param FormRepositoryInterface $formRepository
     */
    public function __construct(
        Action\Context $context,
        FormRepositoryInterface $formRepository
    )
    {
        parent::__construct($context);
        $this->formRepository = $formRepository;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $formId) {
                    /** @var \Codilar\DynamicForm\Model\Data\Form $form */
                    $form = $this->formRepository->load($formId);
                    $this->populateDataModel($form, $postItems[$formId]);
                    try {
                        $this->formRepository->save($form);
                        $messages[] = __("Form \"%1\" saved", $form->getIdentifier());
                    } catch (\Exception $e) {
                        $messages[] = __("Error saving form. Please try again");
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

    /**
     * @param AbstractExtensibleObject $dataModel
     * @param array $data
     * @return mixed
     */
    protected function populateDataModel($dataModel, $data) {
        foreach ($data as $key => $value) {
            $dataModel->setData($key, $value);
        }
        return $dataModel;
    }
}