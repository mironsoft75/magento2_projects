<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Post;
use Magento\Framework\App\Action;

class Index  extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Amasty\Blog\Model\Posts
     */
    private $postsModel;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Amasty\Blog\Model\View
     */
    private $viewModel;
    /**
     * @var \Amasty\Blog\Helper\Url
     */
    private $urlHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Amasty\Blog\Model\Posts $postsModel,
        \Amasty\Blog\Helper\Url $urlHelper,
        \Amasty\Blog\Model\View $viewModel
    ) {
        parent::__construct($context);
        $this->postsModel = $postsModel;
        $this->registry = $registry;
        $this->viewModel = $viewModel;
        $this->urlHelper = $urlHelper;
    }

    public function execute()
    {
        $postId = $this->getRequest()->getParam("id");
        if ($postId){

            $post = $this->postsModel->load($postId);
            if ($post->getId()){

                $this->registry->register('current_post', $post, true);

                $request = $this->getRequest();
                $this->viewModel->registerMe($request, $this->_getRefererUrl());

                $this->_view->loadLayout();
                $this->_view->renderLayout();
            } else {
                $this->getResponse()->setRedirect($this->urlHelper->getUrl());
            }

        } else {
            $this->getResponse()->setRedirect($this->urlHelper->getUrl());
        }
    }

}
