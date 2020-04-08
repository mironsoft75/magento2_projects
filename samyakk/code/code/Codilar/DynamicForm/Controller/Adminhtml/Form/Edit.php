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
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Backend\Model\View\Result\Page;

class Edit extends Action
{
    const ADMIN_RESOURCE = "Codilar_DynamicForm::form";
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * Edit constructor.
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
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        try {
            if ($id) {
                $form = $this->formRepository->load($id);
            }
            /** @var Page $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $result->setActiveMenu("Codilar_DynamicForm::form")
                ->addBreadcrumb(
                    __("Dynamic Form"),
                    __("Dynamic Form")
                )
                ->addBreadcrumb(
                    __("Form"),
                    __("Form"),
                    $this->getUrl('*/*')
                )
                ->addBreadcrumb(
                    $id ? __("Edit Form") : __("Add Form"),
                    $id ? __("Edit Form") : __("Add Form")
                );
            $result->getConfig()->getTitle()->prepend(__('Forms'));
            $result->getConfig()->getTitle()->prepend($id ? $form->getTitle() : __('New Form'));
            return $result;
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__("This form no longer exists"));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath("*/*");
        }
    }
}