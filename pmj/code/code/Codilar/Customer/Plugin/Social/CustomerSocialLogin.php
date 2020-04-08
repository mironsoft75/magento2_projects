<?php
namespace Codilar\Customer\Plugin\Social;
use Magento\Framework\Registry;

/**
 * Class Login
 * @package Mageplaza\SocialLogin\Controller\Social
 */
class CustomerSocialLogin 
{   
    
    /**
    *@var Registry
    */
    protected $registry;

    /**
     * Social Login constructor.
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    )
    {
        $this->registry = $registry;
    }

    /**
     * @param \Mageplaza\SocialLogin\Controller\Social\Login $login
     * @return array
     */
    public function beforeExecute(\Mageplaza\SocialLogin\Controller\Social\Login $login)
    {
        $this->registry->register('is_social_login', true);
        return array();
    }
   
}