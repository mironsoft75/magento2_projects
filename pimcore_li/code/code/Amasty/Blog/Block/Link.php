<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block;

use Amasty\Blog\Helper\Settings;

class Link extends \Magento\Framework\View\Element\Html\Link\Current
{

    /**
     * @var Settings
     */
    private $settingsHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Helper\Settings $settingsHelper,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        array $data =[]
    ) {
        parent::__construct($context, $defaultPath, $data);
        $this->settingsHelper = $settingsHelper;
    }

    public function getPath()
    {
        return $this->settingsHelper->getSeoRoute();
    }

    public function getLabel()
    {
        return $this->settingsHelper->getFooterLabel();
    }
}

