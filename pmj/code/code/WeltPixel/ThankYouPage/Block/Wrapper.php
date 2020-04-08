<?php
namespace WeltPixel\ThankYouPage\Block;

/**
 * Class Wrapper
 * @package WeltPixel\ThankYouPage\Block
 */
class Wrapper extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \WeltPixel\ThankYouPage\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \WeltPixel\ThankYouPage\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \WeltPixel\ThankYouPage\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
    }

    /**
     * Get the block elements in desired sort order for output display
     * @return array
     */
    public function getBlockElements() {
        return $this->_helper->getAvailableBlockElements();
    }

    /**
     * Render the html content of a block/container from the layout
     * @param string $elementName
     * @return string
     */
    public function getBlockHtml($elementName) {
        return $this->_layout->renderElement($elementName);
    }

    /**
     * Dynamically render out the blocks/containers for this wrapper block
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $html = '';
        foreach ($this->getBlockElements() as $blockElement) {
            $html .= $this->getBlockHtml($blockElement);
        }

        return $html;
    }
}
