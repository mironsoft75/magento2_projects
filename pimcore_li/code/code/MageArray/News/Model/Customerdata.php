<?php

namespace MageArray\News\Model;

use Magento\Customer\Model\Session;

/**
 * Class Customerdata
 * @package MageArray\News\Model
 */
class Customerdata
{
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * Customerdata constructor.
     * @param Session $customerSession
     */
    public function __construct(
        Session $customerSession,
        \Magento\Framework\App\Http\Context $context
    ) {
        $this->customerSession = $customerSession;
        $this->context = $context;
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->context->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }
}
