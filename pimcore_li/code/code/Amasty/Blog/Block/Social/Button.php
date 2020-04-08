<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Social;

class Button extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $resolverInterface;

    /**
     * Button constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Locale\ResolverInterface $resolverInterface
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Locale\ResolverInterface $resolverInterface,
        array $data =[]
    ) {
        parent::__construct($context, $data);
        $this->resolverInterface = $resolverInterface;
    }

    public function getLocaleCode()
    {
        return $this->resolverInterface->getLocale();
    }
}