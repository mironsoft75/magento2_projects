<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block;

class Custom extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    const TRANSFER_KEY = 'MP_BLOG_CUSTOM_WIDGET_TRANSFER_DATA';
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * Custom constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
    }

    protected $_dataToTransfer = array(
        'category_id',
        'display_short',
        'record_limit',
        'display_date',
    );
    
    protected function _toHtml()
    {
        if ($blockType = $this->getBlockType()){

            $transfer = array();
            foreach ($this->_dataToTransfer as $key){
                $transfer[$key] = $this->getData($key);
            }
            
            if ($this->registry->registry(self::TRANSFER_KEY)){
                $this->registry->unregister(self::TRANSFER_KEY);
            }

            $this->registry->register(self::TRANSFER_KEY, $transfer);

            $block = $this->getLayout()->createBlock("Amasty\\Blog\\{$blockType}\\Custom");
            
            if ($block){
                return $block->toHtml();
            }
        }
        return  false;
    }

}