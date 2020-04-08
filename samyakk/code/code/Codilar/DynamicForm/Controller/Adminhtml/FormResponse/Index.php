<?php
/**
 * @package     magento2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller\Adminhtml\FormResponse;


use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Exception\NoSuchEntityException;

class Index extends Action
{
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * Index constructor.
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

    const ADMIN_RESOURCE = "Codilar_DynamicForm::form_response";

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
        $id = $this->getRequest()->getParam('form_id');
        try {
            if ($id) {
                $form = $this->formRepository->load($id);
            }
            /** @var Page $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $result->getConfig()->getTitle()->prepend(__('Forms Response'));
            $pageTitle = "Form Response (" . $form->getTitle() . ")";
            $result->getConfig()->getTitle()->prepend($id ? $pageTitle : __('Form Response'));
            return $result;
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__("This form Response no longer exists"));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath("*/*");
        }
    }
}