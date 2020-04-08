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
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends Action
{
    const ADMIN_RESOURCE = "Codilar_DynamicForm::form";
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * Delete constructor.
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
            $form = $this->formRepository->load($id);
            $this->formRepository->delete($form);
            $this->messageManager->addSuccessMessage(__("Form deleted successfully"));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__("The form no longer exists"));
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}