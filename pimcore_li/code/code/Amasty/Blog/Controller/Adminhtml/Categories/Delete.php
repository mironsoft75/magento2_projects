<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Adminhtml\Categories;

class Delete extends \Amasty\Blog\Controller\Adminhtml\Categories
{
    /**
     * @var \Amasty\Blog\Model\CategoriesFactory
     */
    private $categoriesFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Amasty\Blog\Model\CategoriesFactory $categoriesFactory
    ) {
        parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
        $this->categoriesFactory = $categoriesFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->categoriesFactory->create();
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('You have deleted the category.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a category to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
