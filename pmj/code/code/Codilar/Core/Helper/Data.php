<?php

/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Stdlib\DateTime\Timezone;

class Data extends AbstractHelper
{
    CONST DATEFORMAT = "Y-m-d H:i:s";
    /**
     * @var Timezone
     */
    private $timezone;

    /**
     * Data constructor.
     * @param Context $context
     * @param Timezone $timezone
     */
    public function __construct(
        Context $context,
        Timezone $timezone
    )
    {
        parent::__construct($context);
        $this->timezone = $timezone;
    }

    /**
     * @param string $dateFormat
     * @return string
     */
    public function getCurrentDate($dateFormat = self::DATEFORMAT)
    {
        return $this->timezone->date()->format($dateFormat);
    }
}