<?php

namespace Pimcore\CartOption\Controller\Adminhtml\Rewrite;
use Magento\Framework\Controller\ResultFactory;

class MassDelete extends \Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite
{
    /**
     * Delete one or more subscribers action
     * @return void
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $UrlRewriteIds = $this->getRequest()->getParam('id');
        if (!is_array($UrlRewriteIds)) {
            $this->messageManager->addError(__('Please select one or more subscribers.'));
        } else {
            try {
                foreach ($UrlRewriteIds as $UrlRewriteId) {
                    $UrlRewrite = $this->_objectManager->create(
                        \Magento\UrlRewrite\Model\UrlRewrite::class
                    )->load(
                        $UrlRewriteId
                    );
                    $UrlRewrite->delete();
                }
                $this->messageManager->addSuccess(__('Total of %1 record(s) were deleted.', count($UrlRewriteIds)));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect($this->_redirect->getRefererUrl());
    }
}
