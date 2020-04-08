<?php
namespace Bss\Gallery\Block\Adminhtml\Item\Grid;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

class CheckboxRenderer extends AbstractRenderer
{
    private $_storeManager;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        StoreManagerInterface $storemanager,
        array $data = []
    )
    {
        $this->_storeManager = $storemanager;
        parent::__construct($context, $data);
        $this->_authorization = $context->getAuthorization();
    }

    /**
     * Renders grid column
     *
     * @param Object $row
     * @return  string
     */
    public function render(DataObject $row)
    {
        $current_category = $this->getRequest()->getParam('category_id');
        $list_category = explode(',', $this->_getValue($row));
        array_pop($list_category);
        if (in_array($current_category, $list_category)) {
            return true;
        }
        return false;
    }
}
