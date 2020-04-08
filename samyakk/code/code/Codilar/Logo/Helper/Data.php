<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Logo\Helper;

use Codilar\Api\Helper\Emulator;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Theme\Block\Html\Header\Logo;

class Data extends AbstractHelper
{
    /**
     * @var Logo
     */
    private $logo;
    /**
     * @var Emulator
     */
    private $emulator;

    /**
     * Data constructor.
     * @param Context $context
     * @param Logo $logo
     * @param Emulator $emulator
     */
    public function __construct(
        Context $context,
        Logo $logo,
        Emulator $emulator
    )
    {
        parent::__construct($context);
        $this->logo = $logo;
        $this->emulator = $emulator;
    }

    /**
     * @return string
     */
    public function getLogoSrc()
    {
        $this->emulator->startEmulation();
        $logo = $this->logo->getLogoSrc();
        $this->emulator->stopEmulation();
        return $logo;
    }

    /**
     * @return string
     */
    public function getLogoAlt()
    {
        return $this->logo->getLogoAlt();
    }

    /**
     * @return int
     */
    public function getLogoHeight()
    {
        return $this->logo->getLogoHeight();
    }

    /**
     * @return int
     */
    public function getLogoWidth()
    {
        return $this->logo->getLogoWidth();
    }
}
