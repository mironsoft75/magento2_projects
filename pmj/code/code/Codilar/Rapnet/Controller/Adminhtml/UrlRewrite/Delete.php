<?php

namespace Codilar\Rapnet\Controller\Adminhtml\UrlRewrite;

use Codilar\Rapnet\Model\UrlRewriteFactory;
use Codilar\Rapnet\Model\ResourceModel\UrlRewrite as UrlRewriteResource;
use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Codilar\Rapnet\Controller\Adminhtml\UrlRewrite
 */
class Delete extends Action
{
    /**
     * @var UrlRewriteFactory
     */
    private $urlRewriteFactory;
    /**
     * @var UrlRewriteResource
     */
    private $urlRewriteResource;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param UrlRewriteResource $urlRewriteResource
     */
    public function __construct(
        Action\Context $context,
        UrlRewriteFactory $urlRewriteFactory,
        UrlRewriteResource $urlRewriteResource
    ) {
        parent::__construct($context);
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->urlRewriteResource = $urlRewriteResource;
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('url_rewrite_id');
        $model = $this->urlRewriteFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->urlRewriteResource->load($model, $id);
        if ($id) {
            try {
                $this->urlRewriteResource->delete($model);
                $this->messageManager->addSuccess(__('The UrlRewrite has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['url_rewrite_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find the UrlRewrite to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
