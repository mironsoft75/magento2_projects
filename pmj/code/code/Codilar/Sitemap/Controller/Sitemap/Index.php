<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 14/12/18
 * Time: 4:17 PM
 */

namespace Codilar\Sitemap\Controller\Sitemap;

/**
 * Class Index
 * @package Codilar\Sitemap\Controller\Sitemap
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }
    /**
     * @return mixed
     */

    public function execute()
    {
        $pageFactory = $this->_pageFactory->create();
        return $pageFactory;
    }
}