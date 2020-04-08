<?php

namespace MageArray\News\Block\Adminhtml\Newscat\Grid\Renderer;

use MageArray\News\Model\NewscatFactory;
use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Catname
 * @package MageArray\News\Block\Adminhtml\Newscat\Grid\Renderer
 */
class Catname extends AbstractRenderer
{
    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;
    /**
     * @var NewscatFactory
     */
    protected $_newscatFactory;

    /**
     * Catname constructor.
     * @param Context $context
     * @param NewscatFactory $newscatFactory
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        NewscatFactory $newscatFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
        $this->_newscatFactory = $newscatFactory;
        $this->_authorization = $context->getAuthorization();
    }

    /**
     * @param DataObject $row
     * @return mixed
     */
    public function render(DataObject $row)
    {
        $id = $this->_getValue($row);
        if ($id != 0) {
            $newscatModel = $this->_newscatFactory->create();
            $item = $newscatModel->load($id);
            return $item['cat_name'];
        } else {
            return __('No Parent');
        }
    }
}
