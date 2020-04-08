<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 22/4/19
 * Time: 1:08 PM
 */

namespace Codilar\Body\Block\Frontend;

/**
 * Class BodyScriptArea
 *
 * @package Pimcore\CartOption\Block\Frontend
 *
 */
class BodyScriptArea extends \Magento\Framework\View\Element\Template
{
    /**
     * Body Script
     *
     * @var string
     */
    protected $bodyScript;

    /**
     * BodyScriptArea constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     *
     * @param array $data
     *
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Retrieve copyright information
     *
     * @return string
     */
    public function getBodyScript()
    {
        if (!$this->bodyScript) {
            $this->bodyScript = $this->_scopeConfig->getValue(
                'design/body/script',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }
        return __($this->bodyScript);
    }



}