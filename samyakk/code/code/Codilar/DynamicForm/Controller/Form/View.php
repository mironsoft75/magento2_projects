<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller\Form;


use Codilar\DynamicForm\Api\Data\FormInterface;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\Page;

class View extends Action
{
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * View constructor.
     * @param Context $context
     * @param FormRepositoryInterface $formRepository
     */
    public function __construct(
        Context $context,
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
        try {
            /** @var Page $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $form = $this->formRepository->load($this->getRequest()->getParam('id'));
            $this->prepareResultPage($resultPage, $form);
            return $resultPage;
        } catch (NoSuchEntityException $e) {
            throw new NotFoundException(__($e->getMessage()));
        }
    }

    /**
     * @param Page $resultPage
     * @param FormInterface $form
     */
    protected function prepareResultPage(Page $resultPage, FormInterface $form)
    {
        $config = $resultPage->getConfig();
        $config->getTitle()->set($form->getTitle());
        $config->setDescription($form->getTitle());
        $config->setMetaTitle($form->getTitle());
        $config->setPageLayout('1column');
        $config->addBodyClass('dynamicform-form-' . $form->getIdentifier());
    }
}