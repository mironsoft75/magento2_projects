<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 30/7/19
 * Time: 11:58 PM
 */

namespace Codilar\ViewCache\Controller\Adminhtml\Cache;

use Codilar\ViewCache\Helper\Data;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;

class ViewCache extends Action
{
    /**
     * @var Data
     */
    private $helper;

    public function __construct(
        Context $context,
        Data $helper
    ) {
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $tag ="*";
            $response = $this->helper->urlExecute($tag);
            if ($response['status']) {
                $this->messageManager->addSuccessMessage($response['message']);
            } else {
                $this->messageManager->addErrorMessage($response['message']);
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(__('An error occurred while clearing the Vue cache.'));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('adminhtml/*');
    }
}
