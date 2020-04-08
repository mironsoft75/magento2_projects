<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\Media;

/**
 * Media Image Model
 */
class Image extends \Magento\Catalog\Model\Product\Image
{
    const POSTION_TOP     = 'top';
    const POSITION_BOTTOM = 'bottom';
    const POSITION_CENTER = 'center';
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Media\Config $catalogProductMediaConfig,
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Image\Factory $imageFactory,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\FileSystem $viewFileSystem,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $storeManager, $catalogProductMediaConfig, $coreFileStorageDatabase, $filesystem, $imageFactory, $assetRepo, $viewFileSystem, $scopeConfig, $resource, $resourceCollection, $data);
        $this->directoryList = $directoryList;
    }

    /**
     * Crop position from top
     *
     * @var float
     */
    protected $_topRate = 0.5;

    /**
     * Crop position from bootom
     *
     * @var float
     */
    protected $_bottomRate = 0.5;

    protected $_isAdaptive = false;

    /**
     * Set if this image has adaptive resize
     *
     * @param $isAdaptive
     * @return $this
     */
    public function setIsAdaptive($isAdaptive)
    {
        $this->_isAdaptive = $isAdaptive;
        return $this;
    }

    /**
     * Set if this image has adaptive resize
     *
     * @return boolean
     */
    public function getIsAdaptive()
    {
        return $this->_isAdaptive;
    }
    
    public function resize()
    {
        parent::resize();
        return $this;
    }
    
    public function adaptiveResize()
    {
        if (is_null($this->getWidth())) {
            return $this;
        }

        if (is_null($this->getHeight())) {
            $this->setHeight($this->getWidth());
        }

        $processor = $this->getImageProcessor();

        $currentRatio = $processor->getOriginalWidth() / $processor->getOriginalHeight();
        $targetRatio = $this->getWidth() / $this->getHeight();

        if ($targetRatio > $currentRatio) {
            $processor->resize($this->getWidth(), null);
        } else {
            $processor->resize(null, $this->getHeight());
        }

        $diffWidth  = $processor->getOriginalWidth() - $this->getWidth();
        $diffHeight = $processor->getOriginalHeight() - $this->getHeight();

        $processor->crop(
            floor($diffHeight * $this->_topRate),
            floor($diffWidth / 2),
            ceil($diffWidth / 2),
            ceil($diffHeight * $this->_bottomRate)
        );

        return $this;
    }

    public function setCropPosition($position)
    {
        switch ($position) {
            case self::POSTION_TOP:
                $this->_topRate    = 0;
                $this->_bottomRate = 1;
                break;
            case self::POSITION_BOTTOM:
                $this->_topRate    = 1;
                $this->_bottomRate = 0;
                break;
            default:
                $this->_topRate    = 0.5;
                $this->_bottomRate = 0.5;
        }
        return $this;
    }
    
    public function setBaseFile($file)
    {
        $file = trim($file, "/");
        $file = "/".$file;
        $baseFile = $this->directoryList->getPath('media').str_replace("/", \DS, $file);

        if ((!$file) || (!file_exists($baseFile))) {
            throw new \Exception(__('Image file was not found.'));
        }

        $this->_baseFile = $baseFile;
        

        return $this;
    }

}