<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller\Adminhtml\Element;


use Codilar\DynamicForm\Api\Form\ElementRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Api\AbstractExtensibleObject;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

class InlineEdit extends Action
{

    const ADMIN_RESOURCE = "Codilar_DynamicForm::form_element";
    /**
     * @var ElementRepositoryInterface
     */
    private $elementRepository;

    /**
     * InlineEdit constructor.
     * @param Action\Context $context
     * @param ElementRepositoryInterface $elementRepository
     */
    public function __construct(
        Action\Context $context,
        ElementRepositoryInterface $elementRepository
    )
    {
        parent::__construct($context);
        $this->elementRepository = $elementRepository;
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
                foreach (array_keys($postItems) as $elementId) {
                    /** @var \Codilar\DynamicForm\Model\Data\Form\Element $element */
                    $element = $this->elementRepository->load($elementId);
                    $this->populateDataModel($element, $postItems[$elementId]);
                    try {
                        $this->elementRepository->save($element);
                        $messages[] = __("Form element \"%1\" saved", $element->getIdentifier());
                    } catch (\Exception $e) {
                        $messages[] = __("Error saving form element. Please try again");
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