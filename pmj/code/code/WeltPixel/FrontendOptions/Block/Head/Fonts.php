<?php

namespace WeltPixel\FrontendOptions\Block\Head;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Fonts extends \Magento\Framework\View\Element\Template {

    /**
     * @var \WeltPixel\FrontendOptions\Helper\Fonts
     */
    protected $_helper;
    
    /**
     * @param \WeltPixel\FrontendOptions\Helper\Fonts $_helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \WeltPixel\FrontendOptions\Helper\Fonts $_helper,
        \Magento\Framework\View\Element\Template\Context $context, array $data = []
    ) {
        $this->_helper = $_helper;
        parent::__construct($context, $data);
    }

    /**
     * Get the google font url to import in the head section
     * @return boolean|string
     */
    public function getGoogleFonts() {
        
        $googleFonts = $this->_helper->getGoogleFonts();
        return $googleFonts;
    }

}
