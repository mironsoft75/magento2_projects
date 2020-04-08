<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    pmj-internal
 * @package    pmj-internal
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     pmj-internal
 * @author       Codilar Team
 **/

namespace Codilar\ProductImageAndVideos\Controller\Adminhtml\Video;


use Magento\Setup\Exception;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;


class AddVideo extends Action
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
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
     * Execute Add Video Indexer
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        shell_exec(' yes y | php /var/www/html/bin/magento indexer:reindex codilar_product_videos >/var/www/html/var/log/video_indexer.log 2>/var/www/html/var/log/video_indexer.log &');

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(__('Add Videos'), __('Add Videos'));
        $resultPage->getConfig()->getTitle()->prepend(__('Video will be added in few time'));

        return $resultPage;
    }

}