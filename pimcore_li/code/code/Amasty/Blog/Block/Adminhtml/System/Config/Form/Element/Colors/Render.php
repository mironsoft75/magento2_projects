<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\System\Config\Form\Element\Colors;

class Render
    extends \Magento\Backend\Block\Template
{
    /**
     * @var \Amasty\Blog\Helper\Config
     */
    private $helperConfig;

    /**
     * Render constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Amasty\Blog\Helper\Config $helperConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\Blog\Helper\Config $helperConfig,
        array $data = []
    ) {

        parent::__construct($context, $data);
        $this->helperConfig = $helperConfig;
    }

    protected function _construct()
    {

        $this->setTemplate('Amasty_Blog::system/config/form/elements/colors.phtml');
        parent::_construct();
    }

    public function getSchemesData()
    {
        $data = array();
        $schemeKeys = $this->helperConfig->getArrayFromPath('color_schemes');
        foreach ($schemeKeys as $key=>$value){
            if ($value && isset($value['data'])){
                $data[$key] = $value['data'];
            }
        }
        return $data;
    }

    public function getSchemesDataJson()
    {
        return \Zend_Json::encode($this->getSchemesData());
    }

    public function getSchemes()
    {
        $schemeKeys = $this->helperConfig->getArrayFromPath('color_schemes');
        $schemes = [];

        $schemes['_select_'] = __("Select one and press Apply");
        foreach ($schemeKeys as $key=>$value){
            $schemes[$key] = __($value['label']);
        }

        return $schemes;
    }
}

