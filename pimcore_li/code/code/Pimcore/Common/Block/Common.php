<?php

namespace Pimcore\Common\Block;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Common
 * @package Pimcore\Common\Block
 */
class Common extends \Magento\Framework\View\Element\Template
{
    /**
     * Common constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getCompanyInfo()
    {
        $html = <<<HTML
        <a style="display:none;" href=""></a>
HTML;
        return $html;
    }
}
