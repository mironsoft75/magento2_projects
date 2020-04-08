<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 21/11/18
 * Time: 6:12 PM
 */

namespace Codilar\Videostore\Controller\Cart;

use Codilar\Videostore\Api\VideostoreCartRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Exception\LocalizedException;

class Delete extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $pageFactory;
    /**
     * @var \Codilar\Videostore\Api\VideostoreCartRepositoryInterface
     */
    private $videostoreCartRepository;
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * Delete constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param VideostoreCartRepositoryInterface $videostoreCartRepository
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        VideostoreCartRepositoryInterface $videostoreCartRepository,
        JsonFactory $resultJsonFactory
    )
    {
        $this->pageFactory = $pageFactory;
        $this->videostoreCartRepository = $videostoreCartRepository;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        try{
            if($this->getRequest()->getParam('product-id')){
                $productId = $this->getRequest()->getParam('product-id');
                $this->videostoreCartRepository->deleteById($productId);
                $this->messageManager->addSuccessMessage('Product deleted successfully');
                $result->setData(['success' => true,'value'=>'Product deleted successfully']);
            }
            else{
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setRefererOrBaseUrl();
            }
        }
        catch (\Exception $exception){
            $result->setData(['success' => false,'value'=>'Failed to delete from cart']);
            throw new LocalizedException(__('Error deleting the product '. $exception->getMessage()));
        }
    }
}