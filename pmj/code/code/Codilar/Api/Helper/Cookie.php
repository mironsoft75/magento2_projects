<?php

namespace Codilar\Api\Helper;

use \Magento\Framework\App\Helper\Context;
use \Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Cookie extends AbstractHelper{

    const COOKIE_LIFETIME = 60*24*3600; //60 days

    protected $_encryptor,
        $_cookieManager,
        $_cookieMetadataFactory,
        $_sessionManager;

    public function __construct(
        Context $context,
        EncryptorInterface $encryptor,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    )
    {
        $this->_encryptor = $encryptor;
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_sessionManager = $sessionManager;
        parent::__construct($context);
    }

    public function getPhpSessionCookie(){
        return session_id();
    }

    public function getSessionCookieName(){
        return session_name();
    }

    public function set($name, $value){
        $metadata = $this->_cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration(self::COOKIE_LIFETIME)
            ->setPath($this->_sessionManager->getCookiePath())
            ->setDomain($this->_sessionManager->getCookieDomain());
        $this->_cookieManager->setPublicCookie($name, $value, $metadata);
    }

    public function get($name){
        return $this->_cookieManager->getCookie($name);
    }

    public function delete($name){
        $this->_cookieManager->deleteCookie(
            $name,
            $this->_cookieMetadataFactory
                ->createCookieMetadata()
                ->setPath($this->_sessionManager->getCookiePath())
                ->setDomain($this->_sessionManager->getCookieDomain()));
    }
}