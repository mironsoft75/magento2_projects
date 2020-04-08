<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;

class Resize extends AbstractHelper{

    protected $_filesystem;
    protected $_storeManager;
    protected $_directory;
    protected $_imageFactory;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Framework\App\Helper\Context $context
    ) {

        parent::__construct($context);
        $this->_filesystem = $filesystem;
        $this->_storeManager = $storeManager;
        $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_imageFactory = $imageFactory;
    }

    public function imageResize(
        $src,
        $width=200,
        $height=200,
        $dir='/amasty/blog/'
    ){
        $absPath = $this->_filesystem
                ->getDirectoryRead(DirectoryList::MEDIA)
                ->getAbsolutePath($dir).$src;

        $imageResized = $this->_filesystem
                ->getDirectoryRead(DirectoryList::MEDIA)
                ->getAbsolutePath($dir).
            $this->getNewDirectoryImage($src, $width, $height);
        $imageResize = $this->_imageFactory->create();

        $imageResize->open($absPath);
        $imageResize->backgroundColor([255, 255, 255]);
        $imageResize->constrainOnly(true);
        $imageResize->keepTransparency(true);
        $imageResize->keepFrame(true);
        $imageResize->keepAspectRatio(true);

        $imageResize->resize($width,$height);
        $dest = $imageResized ;
        $imageResize->save($dest);
        $resizedURL= $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).
            $dir.$this->getNewDirectoryImage($src, $width, $height);
        return $resizedURL;

    }

    public function getNewDirectoryImage($src, $width, $height)
    {
        $segments = array_reverse(explode('/',$src));
        $first_dir = substr($segments[0],0,1);
        $second_dir = substr($segments[0],1,1);
        return 'cache/'.$first_dir.'/'.$second_dir.'/'.$width.'/'.$height.'/'.$segments[0];
    }
}