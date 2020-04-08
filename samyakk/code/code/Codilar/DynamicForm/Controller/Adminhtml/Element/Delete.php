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
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends Action
{
    const ADMIN_RESOURCE = "Codilar_DynamicForm::form_element";
    /**
     * @var ElementRepositoryInterface
     */
    private $elementRepository;

    /**
     * Delete constructor.
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
            $form = $this->elementRepository->load($id);
            $this->elementRepository->delete($form);
            $this->messageManager->addSuccessMessage(__("Form element deleted successfully"));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__("The form element no longer exists"));
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}