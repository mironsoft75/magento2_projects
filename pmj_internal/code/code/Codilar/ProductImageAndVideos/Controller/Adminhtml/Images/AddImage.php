<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    pmj-internal
 * @package    pmj-internal
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     pmj-internal
 * @author       Codilar Team
 **/

namespace Codilar\ProductImageAndVideos\Controller\Adminhtml\Images;

use Magento\Setup\Exception;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class AddImage
 * @package Codilar\ProductImageAndVideos\Controller\Adminhtml\Images
 */
class AddImage extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * AddImage constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute Image Indexer
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        shell_exec(' yes y | php /var/www/html/bin/magento indexer:reindex codilar_product_images >/var/www/html/var/log/images_indexer.log 2>/var/www/html/var/log/images_indexer.log &');

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(__('Add Images'), __('Add Images'));
        $resultPage->getConfig()->getTitle()->prepend(__('Images will be added in few time'));
        return $resultPage;
    }
}