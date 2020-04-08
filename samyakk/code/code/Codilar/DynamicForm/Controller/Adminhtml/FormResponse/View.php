<?php
/**
 * @package     magento2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller\Adminhtml\FormResponse;

use Codilar\DynamicForm\Api\Form\ResponseRepositoryInterface;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Exception\NoSuchEntityException;

class View extends Action
{
    /**
     * @var ResponseRepositoryInterface
     */
    private $responseRepository;
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * View constructor.
     * @param Action\Context $context
     * @param ResponseRepositoryInterface $responseRepository
     * @param FormRepositoryInterface $formRepository
     */
    public function __construct(
        Action\Context $context,
        ResponseRepositoryInterface $responseRepository,
        FormRepositoryInterface $formRepository
    )
    {
        parent::__construct($context);
        $this->responseRepository = $responseRepository;
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
        try {
            $id = $this->getRequest()->getParam('id');
            $response = $this->responseRepository->load($id);
            try {
                $title = $this->formRepository->load($response->getFormId())->getTitle();
            } catch (NoSuchEntityException $exception) {
                $title = "";
            }
            /** @var Page $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $pageTitle = __("%title | Form Response #%id", [
                'id'    =>  $response->getId(),
                'title' =>  $title
            ]);
            $result->getConfig()->getTitle()->prepend($pageTitle);
            return $result;
        } catch (NoSuchEntityException $noSuchEntityException) {
            $this->messageManager->addErrorMessage(__("The response does not exist"));
            return $this->resultRedirectFactory->create()->setPath('dynamicform/formresponse/responses');
        }
    }
}