<?php
/**
 * Created by PhpStorm.
 * User: mmjsm
 * Date: 30/8/17
 * Time: 12:57 PM
 */

namespace Codilar\Offers\Block\Adminhtml\Catalog\Product\Edit\Tab;


use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Store extends Widget implements TabInterface

{

    protected $_template = 'catalog/product/edit/tab/custom.phtml';


    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {

        return parent::_prepareLayout();
    }

    /**
     * Return Tab label
     *
     * @return string
     * @api
     */
    public function getTabLabel()
    {
        // TODO: Implement getTabLabel() method.
    }

    /**
     * Return Tab title
     *
     * @return string
     * @api
     */
    public function getTabTitle()
    {
        // TODO: Implement getTabTitle() method.
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     * @api
     */
    public function canShowTab()
    {
        // TODO: Implement canShowTab() method.
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     * @api
     */
    public function isHidden()
    {
        // TODO: Implement isHidden() method.
    }
}