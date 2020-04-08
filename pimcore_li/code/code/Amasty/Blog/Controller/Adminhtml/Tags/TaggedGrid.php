<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Adminhtml\Tags;

use Magento\Backend\App\Action;
use Magento\Framework\ObjectManagerInterface;

class TaggedGrid extends Action
{
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * TaggedGrid constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);

        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->_coreRegistry = $registry;
    }

    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        $posts = $this->_objectManager->create('Amasty\Blog\Model\Posts');

        if ($id = (int)$this->_request->getParam('id')) {
            $postsCollection = $posts->getTaggedPosts($id);
            $this->_coreRegistry->register('amasty_blog_current_posts', $postsCollection);
        }

        return $resultLayout;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_Blog::tags');
    }
}
