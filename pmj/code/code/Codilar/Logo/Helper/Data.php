<?php

namespace Codilar\Logo\Helper;

use \Codilar\Logo\Model\Config\Backend\Image;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Data constructor.
     * @param Context $context
     * @param TypeListInterface $typeList
     */
    public function __construct(
        Context $context,
    StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }

    /**
     * @param $config_path
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getLogoPath()
    {
        $mediaUrl =$this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $image =$mediaUrl .Image::UPLOAD_DIR;
        if(substr($image,-1) === '/') {
            return $image.$this->getConfig('codilar_admin/logo_group/admin_login');
        }
        else {
            $path =$image.'/'.$this->getConfig('codilar_admin/logo_group/admin_login');
            return $path;
        }
    }

    public function getAdminMenuPath()
    {   $mediaUrl =$this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $image =$mediaUrl .Image::UPLOAD_DIR;
        if(substr($image,-1) === '/') {
            return $image.$this->getConfig('codilar_admin/logo_group/admin_main_menu');
        }
      else {
          $path =$image.'/'.$this->getConfig('codilar_admin/logo_group/admin_main_menu');
       return $path;
      }
    }



}