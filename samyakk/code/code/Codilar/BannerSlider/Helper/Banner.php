<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory as ImageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magestore\Bannerslider\Model\Banner as BannerModel;
use Magestore\Bannerslider\Model\ResourceModel\Banner\CollectionFactory as BannerCollectionFactory;

class Banner extends AbstractHelper
{

    const DEVICE_DESKTOP = 0;
    const DEVICE_MOBILE = 1;

    /**
     * @var array
     */
    private $dimensions = [];

    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var ImageFactory
     */
    private $imageFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var BannerCollectionFactory
     */
    private $bannerCollectionFactory;

    /**
     * Banner constructor.
     * @param Context $context
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     * @param ImageFactory $imageFactory
     * @param StoreManagerInterface $storeManager
     * @param BannerCollectionFactory $bannerCollectionFactory
     */
    public function __construct(
        Context $context,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        ImageFactory $imageFactory,
        StoreManagerInterface $storeManager,
        BannerCollectionFactory $bannerCollectionFactory
    )
    {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
        $this->imageFactory = $imageFactory;
        $this->storeManager = $storeManager;
        $this->bannerCollectionFactory = $bannerCollectionFactory;
    }


    /**
     * @param $banner
     * @param int $device
     * @return string
     * @throws \Exception
     */
    public function createOrGetImage($banner, $device = self::DEVICE_DESKTOP) {
        $resizedImagePath = $this->getPath($device);
        $resizedImageName = $this->getResizedImageName($banner);
        $resizedImage = $resizedImagePath."/".$resizedImageName;

        /* Create resized image */
        if(!file_exists($resizedImage)) {
            $originalImagePath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($banner->getData('image'));
            $imageResizer = $this->imageFactory->create();
            $imageResizer->open($originalImagePath);
            $imageResizer->resize($this->getWidth($device), $this->getHeight($device));
            $imageResizer->save($resizedImagePath, $resizedImageName);
        }

        return $this->getRelativePath($device)."/".$resizedImageName;
    }

    /**
     * @param BannerModel $banner
     * @return string
     */
    public function getResizedImageName($banner) {
        $originalImage = explode(".", $banner->getData('image'));
        $originalImageName = $originalImage[0];
        $imageType = $originalImage[1];
        $originalImageName = str_replace("/", "-", str_replace(BannerModel::BASE_MEDIA_PATH."/", "", $originalImageName));
        return $originalImageName."-".$banner->getId().".".$imageType;
    }

    /**
     * @param int $device
     * @return string
     */
    public function getPath($device) {
        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $path = $this->getRelativePath($device);
        return $mediaDirectory->getAbsolutePath($path);
    }

    /**
     * @param $device
     * @return string
     */
    public function getRelativePath($device) {
        $dimensions = $this->getDimensions($device);
        $width = $dimensions[0];
        $height = $dimensions[1];
        return BannerModel::BASE_MEDIA_PATH."/cache/$device/$width/$height";
    }

    /**
     * @param $device
     * @return array
     */
    public function getDimensions($device) {
        $dimensions = [];
        switch ($device) {
            case self::DEVICE_DESKTOP:
                $dimensions = explode("x", trim($this->scopeConfig->getValue('homepage/banner_images/website')));
                break;
            case self::DEVICE_MOBILE:
                $dimensions = explode("x", trim($this->scopeConfig->getValue('homepage/banner_images/mobile')));
                break;
        }
        return $dimensions;
    }

    /**
     * @param $device
     * @return mixed
     */
    public function getWidth($device) {
        return $this->getDimensions($device)[0];
    }

    /**
     * @param $device
     * @return mixed
     */
    public function getHeight($device) {
        return $this->getDimensions($device)[1];
    }

    /**
     * @param $sliderId
     * @return bool|\Magestore\Bannerslider\Model\ResourceModel\Banner\Collection
     */
    public function getBannerCollection($sliderId)
    {
        $collection = $this->bannerCollectionFactory->create();
        $collection->addFieldToFilter('slider_id', $sliderId);
        if ($collection->getData()) {
            return $collection;
        } else {
            return false;
        }
    }

}
