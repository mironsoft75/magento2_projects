<?php

namespace MageArray\News\Block\Adminhtml\Newscomment\Grid\Renderer;

use MageArray\News\Model\NewspostFactory;
use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Postname
 * @package MageArray\News\Block\Adminhtml\Newscomment\Grid\Renderer
 */
class Postname extends AbstractRenderer
{
    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;
    /**
     * @var NewspostFactory
     */
    protected $_modelNewsFactory;

    /**
     * Postname constructor.
     * @param Context $context
     * @param NewspostFactory $modelNewsFactory
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        NewspostFactory $modelNewsFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
        $this->_modelNewsFactory = $modelNewsFactory;
        $this->_authorization = $context->getAuthorization();
    }

    /**
     * @param DataObject $row
     * @return mixed
     */
    public function render(DataObject $row)
    {
        $id = $this->_getValue($row);
        $newsModel = $this->_modelNewsFactory->create();
        $item = $newsModel->load($id);
        return $item['title'];
    }
}
