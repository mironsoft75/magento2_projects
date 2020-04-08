<?php

namespace Codilar\Rapnet\Controller\Adminhtml\UrlRewrite;

use Codilar\Rapnet\Model\UrlRewriteFactory;
use Codilar\Rapnet\Model\ResourceModel\UrlRewrite as UrlRewriteResource;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * @package Codilar\Rapnet\Controller\Adminhtml\UrlRewrite
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var UrlRewriteFactory
     */
    private $urlRewriteFactory;
    /**
     * @var UrlRewriteResource
     */
    private $urlRewriteResource;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param UrlRewriteResource $urlRewriteResource
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        UrlRewriteFactory $urlRewriteFactory,
        UrlRewriteResource $urlRewriteResource
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->urlRewriteResource = $urlRewriteResource;
    }

    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \Codilar\Rapnet\Model\ShippingFactory $model */
            $model = $this->urlRewriteFactory->create();
            if (empty($data['url_rewrite_id'])) {
                $data['url_rewrite_id'] = null;
            }

            $id = $this->getRequest()->getParam('url_rewrite_id');
            if ($id) {
                $this->urlRewriteResource->load($model, $data['url_rewrite_id']);
            }

            $model->setData($data);
            try {
                $this->urlRewriteResource->save($model);
                $this->dataPersistor->clear('codilar_rapnet_urlrewrite_data');
                $this->messageManager->addSuccessMessage(__('You saved the urlrewrite.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('rapnet/urlrewrite/edit', ['url_rewrite_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __($e->getMessage()));
            }
            $this->dataPersistor->set('codilar_rapnet_urlrewrite_data', $data);
            return $resultRedirect->setPath('*/*/edit', ['url_rewrite_id' => $this->getRequest()->getParam('url_rewrite_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("Codilar_Rapnet::codilar_urlrewrite_save");
    }
}
