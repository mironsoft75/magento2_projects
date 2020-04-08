<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Cms\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    CONST FOOTER_IDENTIFIER = "footer_block";

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function getFooterBlockIdentifier()
    {
        return $this->getValue(self::FOOTER_IDENTIFIER);
    }

    /**
     * @param string $field
     * @param string $group
     * @param string $section
     * @return string
     */
    protected function getValue($field, $group = "general", $section = "footer_api")
    {
        return $this->scopeConfig->getValue($section."/".$group."/".$field);
    }
}
