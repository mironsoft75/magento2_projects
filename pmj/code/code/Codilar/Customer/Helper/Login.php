<?php

namespace Codilar\Customer\Helper;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Login
 * @package Codilar\Customer\Helper
 */
class Login {

    const LOGIN_PREVIOUS_PAGE_PATH = "login_previous_page_path";
    /**
     * @var RedirectInterface
     */
    private $_redirect;
    /**
     * @var Session
     */
    private $_session;
    /**
     * @var ResponseInterface
     */
    private $_response;
    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * Login constructor.
     * @param RedirectInterface $redirect
     * @param Session $session
     * @param ResponseInterface $response
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RedirectInterface $redirect,
        Session $session,
        ResponseInterface $response,
        StoreManagerInterface $storeManager
    )
    {
        $this->_redirect = $redirect;
        $this->_session = $session;
        $this->_response = $response;
        $this->_storeManager = $storeManager;
    }

    /**
     * @param $url
     */
    public function setAfterLoginUrl($url){
        $this->_session->setData(self::LOGIN_PREVIOUS_PAGE_PATH, $url);
    }

    /**
     * @param bool $flush
     * @return mixed
     */
    public function getAfterLoginUrl($flush = true){
        $url = $this->_session->getData(self::LOGIN_PREVIOUS_PAGE_PATH);
        if($flush) $this->setAfterLoginUrl(null);
        return $url;
    }

    /**
     * @return mixed|string
     */
    public function getRefererUrl(){
        $refererUrl = $this->_redirect->getRefererUrl();
        if($refererUrl == $this->getCurrentUrl()){
            return $this->getAfterLoginUrl();
        }
        return $refererUrl;
    }

    /**
     * @param $url
     */
    public function redirect($url){
        $this->_response->setRedirect($url)->sendResponse();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentUrl(){
        return $this->_storeManager->getStore()->getCurrentUrl();
    }

}