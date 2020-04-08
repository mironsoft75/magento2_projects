<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Scope config Provider model
 */
class ConfigProvider
{
    /**
     * xpath prefix of module
     */
    const PATH_PREFIX = 'amblog';

    /**#@+
     * Constants defined for xpath of system configuration
     */
    const IS_ASK_EMAIL = 'comments/ask_email';

    const IS_ASK_NAME = 'comments/ask_name';

    const IS_SHOW_GDPR = 'comments/gdpr';

    const GDPR_TEXT = 'comments/gdpr_text';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ConfigProvider constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * An alias for scope config with default scope type SCOPE_STORE
     *
     * @param string $key
     * @param string|null $scopeCode
     * @param string $scopeType
     *
     * @return string|null
     */
    public function getValue($key, $scopeCode = null, $scopeType = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(self::PATH_PREFIX . '/' . $key, $scopeType, $scopeCode);
    }

    /**
     * @param string|null $scopeCode
     *
     * @return bool
     */
    public function isAskEmail($scopeCode = null)
    {
        return (bool)$this->getValue(self::IS_ASK_EMAIL, $scopeCode);
    }

    /**
     * @param string|null $scopeCode
     *
     * @return bool
     */
    public function isAskName($scopeCode = null)
    {
        return (bool)$this->getValue(self::IS_ASK_NAME, $scopeCode);
    }

    /**
     * @param string|null $scopeCode
     *
     * @return bool
     */
    public function isShowGdpr($scopeCode = null)
    {
        return (bool)$this->getValue(self::IS_SHOW_GDPR, $scopeCode);
    }

    /**
     * @param string|null $scopeCode
     *
     * @return string
     */
    public function getGdprText($scopeCode = null)
    {
        return $this->getValue(self::GDPR_TEXT, $scopeCode);
    }
}
