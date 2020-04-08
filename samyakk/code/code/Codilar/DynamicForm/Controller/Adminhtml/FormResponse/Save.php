<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/19
 * Time: 7:23 PM
 */

namespace Codilar\DynamicForm\Controller\Adminhtml\FormResponse;


use Codilar\DynamicForm\Api\Form\ResponseRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    /**
     * @var ResponseRepositoryInterface
     */
    private $responseRepository;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param ResponseRepositoryInterface $responseRepository
     */
    public function __construct(
        Action\Context $context,
        ResponseRepositoryInterface $responseRepository
    )
    {
        parent::__construct($context);
        $this->responseRepository = $responseRepository;
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
        $data = $this->getRequest()->getParams();
        if ($data) {
            try {
                $response = $this->responseRepository->load($data['response_id']);
                foreach ($response->getFields() as $field) {
                    if (array_key_exists($field->getName(), $data)) {
                        $field->setValue($data[$field->getName()]);
                    }
                }
                $this->responseRepository->save($response);
                $this->messageManager->addSuccessMessage(__("Response is updated!"));
                return $this->_redirect($this->getUrl($this->_redirect->getRefererUrl()));
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addSuccessMessage(__("Failed to update response!"));
            } catch (CouldNotSaveException $e) {
                $this->messageManager->addSuccessMessage(__("Failed to update response!"));
            }
        }
    }
}