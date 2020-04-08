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
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Backend\Model\View\Result\Page;

class Edit extends Action
{
    const ADMIN_RESOURCE = "Codilar_DynamicForm::form_element";
    /**
     * @var ElementRepositoryInterface
     */
    private $elementRepository;

    /**
     * Edit constructor.
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
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        try {
            if ($id) {
                $formElement = $this->elementRepository->load($id);
            }
            /** @var Page $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $result->setActiveMenu("Codilar_DynamicForm::form_element")
                ->addBreadcrumb(
                    __("Dynamic Form Element"),
                    __("Dynamic Form Element")
                )
                ->addBreadcrumb(
                    __("Form"),
                    __("Form"),
                    $this->getUrl('*/*')
                )
                ->addBreadcrumb(
                    $id ? __("Edit Form Element") : __("Add Form Element"),
                    $id ? __("Edit Form Element") : __("Add Form Element")
                );
            $result->getConfig()->getTitle()->prepend(__('Form Elements'));
            $result->getConfig()->getTitle()->prepend($id ? $formElement->getIdentifier() : __('New Form Element'));
            return $result;
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__("This form element no longer exists"));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath("*/*");
        }
    }
}