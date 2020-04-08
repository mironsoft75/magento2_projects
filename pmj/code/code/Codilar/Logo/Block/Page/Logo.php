<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 15/2/19
 * Time: 4:24 PM
 */

namespace Codilar\Logo\Block\Page;


use Codilar\Logo\Helper\Data;
use Magento\Backend\Block\Page\Header;
use Magento\Framework\App\RequestInterface;


class Logo extends Header
{
    const ADMINLOGO = 'images/magento-logo.svg';
    const MENULOGO = 'images/magento-icon.svg';
    const ADMINURL ='adminhtml_auth_login';
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(\Magento\Backend\Block\Template\Context $context,
                                \Magento\Backend\Model\Auth\Session $authSession,
                                Data $helper,
                               RequestInterface $request,
                                \Magento\Backend\Helper\Data $backendData, array $data = [])
    {
        parent::__construct($context, $authSession, $backendData, $data);
        $this->helper = $helper;
        $this->request = $request;
    }

    public function getViewFileUrl($fileId, array $params = [])
    {
       $url =$this->request->getFullActionName();
       $menuLogo= $this->helper->getConfig('codilar_admin/logo_group/admin_main_menu');
       $adminLogin= $this->helper->getConfig('codilar_admin/logo_group/admin_login');
       if($adminLogin === NULL && $url == self::ADMINURL) {
           $defaultUrl= $this->getDefaultUrl($fileId, $params = []).self::ADMINLOGO;
           return $defaultUrl;
       }
       if($menuLogo === NULL) {
         $defaultUrl= $this->getDefaultUrl($fileId, $params = []).self::MENULOGO;
         return $defaultUrl;
        }
        return $fileId;
    }

    public function getDefaultUrl($fileId ,$params =[])
    {
        $url =rtrim($fileId,'/');
        $params = array_merge(['_secure' => $this->getRequest()->isSecure()], $params);
        $fileId = $this->_assetRepo->getUrlWithParams($fileId, $params);
        $logoUrl= str_replace($url,"",$fileId);
        return$logoUrl;
    }

}