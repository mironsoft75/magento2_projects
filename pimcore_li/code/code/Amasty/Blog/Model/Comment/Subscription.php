<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\Comment;

class Subscription extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_SUBSCRIBED = 2;
    const STATUS_UNSUBSCRIBED = 3;

    protected $_post;
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/comment_subscription');
    }

    public function getOptionsArray()
    {
        return array(
            self::STATUS_SUBSCRIBED => __("Subscribed"),
            self::STATUS_UNSUBSCRIBED => __("Unsubscribed"),
        );
    }

    public function toOptionArray()
    {
        $result = array();
        foreach ($this->getOptionsArray() as $value=>$label){
            $result[] = array('value'=>$value, 'label'=>$label);
        }
        return $result;
    }

    public function loadBySessionId($postId, $sessionId)
    {
        $this->getResource()->loadBySessionId($this, $postId, $sessionId);
        return $this;
    }

    public function loadByEmail($postId, $email)
    {
        $this->getResource()->loadByEmail($this, $postId, $email);
        return $this;
    }

    public function loadByCustomerId($postId, $customerId)
    {
        $this->getResource()->loadByCustomerId($this, $postId, $customerId);
        return $this;
    }

    public function generateHash()
    {
        if (!$this->getHash()){
            $hash = md5(microtime());
            $this->setHash($hash);
        } else {
            //Mage::throwException($this->_helper()->__("Can't generate Subscription Hash twice."));
        }

        return $this;
    }
    
    /**
     * Retrieves unsubscribe URL
     *
     * @return string
     */
    public function getUnsubscribeUrl()
    {
        return $this->_getUrlInstance()->getUrl('mpblog/subscription/unsubscribe', array('h'=> $this->getHash()));
    }

    public function mapCheckbox($value)
    {
        return $value ? self::STATUS_SUBSCRIBED : self::STATUS_UNSUBSCRIBED;
    }
}