<?php

namespace Codilar\Api\Plugin;

use Magento\Authorization\Model\CompositeUserContext as Subject;
use Magento\Customer\Model\Session;

class CompositeUserContext
{

    protected $customerSession;

    public function __construct(
        Session $session
    )
    {
        $this->customerSession = $session;
    }

    public function aftergetUserId(Subject $subject, $userType){
        return $this->customerSession->getId();
    }

    public function aftergetUserType(Subject $subject, $userType){
        return Subject::USER_TYPE_CUSTOMER;
    }
}