<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model;

class View extends AbstractModel
{
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Blog\Model\ResourceModel\View');
    }
    
    public function registerMe($request, $refererUrl = null)
    {
        $this->getResource()->loadByPostAndSession($this, $request->getParam('id'), $this->customerSession->getSessionId());
        if (!$this->getId()){

            try {
                $now = new \Zend_Date();
                $this
                    ->setPostId($request->getParam('id'))
                    ->setCustomerId($this->customerSession->isLoggedIn() ? $this->customerSession->getCustomerId() : null)
                    ->setSessionId($this->customerSession->getSessionId())
                    ->setRemoteAddr($this->remoteAddress->getRemoteAddress(true))
                    ->setStoreId($this->storeManagerInterface->getStore()->getId())
                    ->setCreatedAt($now->toString(\Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT))
                    ->setRefererUrl($refererUrl)
                    ->save()
                ;

            } catch (\Exception $e){

                # Do nothing
            }
        }
        return $this;
    }
}